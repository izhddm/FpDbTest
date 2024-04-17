<?php

namespace FpDbTest;

use FpDbTest\Factory\ParameterHandlerFactory;
use FpDbTest\Interface\DatabaseInterface;
use mysqli;

class Database implements DatabaseInterface
{
    private ?mysqli $mysqli;

    public function __construct(?mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    private function test(string $query, array $args = []): array
    {
        preg_match_all('/(\?d|\?f|\?s|\?a|\?#|\?)|(\{\s*.*?\})/', $query, $matches);

        $result = [];
        $blockNum = 0;
        $subBlockNum = 0;

        foreach ($matches[0] as $key => $match) {
            // Если это параметр с символом '?'
            if (str_starts_with($match, '?')) {
                $result[$key][$match] = $args[$key];
            } // Если это блок с символом '{'
            elseif (str_starts_with($match, '{')) {
                ++$blockNum;
                $blockKey = 'block'.$blockNum;

                preg_match_all('/(\?d|\?f|\?s|\?a|\?#|\?)/', $match, $block);

                foreach ($block[0] as $subBlock) {
                    $result[$blockKey][] = [$subBlock => $args[$key + $subBlockNum]];
                    ++$subBlockNum;
                }

                $subBlockNum = $blockNum;
            }
        }

        return $result;
    }

    public function buildQuery(string $query, array $args = []): string
    {
        $regex = '/\?d|\?f|\?s|\?a|\?#|\?/';  // Регулярное выражение для поиска спецификаторов
        $offset = 0;

        $callback = function ($match) use (&$args, &$offset) {
            $handler = ParameterHandlerFactory::create($match[0]);
            $value = $args[$offset++] ?? null;

            return preg_match('/^SKIP#[1-9][0-9]{4}$/', $value) ? '$value' : $handler->handle($value);
        };

        $template = $this->processConditionalBlocks($query, $args);

        return preg_replace_callback($regex, $callback, $template);
    }

    public function skip(): string
    {
        return 'SKIP#'.rand(10000, 99999);
    }

    private function processConditionalBlocks($query, $params): array|string|null
    {
        // Проходимся по всем блокам и удаляем те, где есть специальное значение SKIP
        return preg_replace_callback('/\{([^{}]*)\}/', function ($match) use ($params) {
            $block = $match[1]; // Получаем содержимое блока

            // Проверяем наличие значения skip() в массиве параметров
            if (preg_match('/SKIP#[1-9][0-9]{4}/', $block)) {
                return '';
            }

            // Если skip() не найдено, возвращаем содержимое блока обратно
            return $block;
        }, $query);
    }
}
