<?php

namespace FpDbTest\Interface;

/**
 * Interface QueryParserInterface
 * Определяет интерфейс для разбора строк запросов и замены аргументов.
 */
interface QueryParserInterface
{
    /**
     * Метод для разбора строки запроса и замены аргументов.
     *
     * @param string $query Строка запроса с заменяемыми местами и условными блоками.
     * @param float $skip Строка для скрытия условного блока
     * @param array  $args  Массив аргументов для замены в запросе.
     *
     * @return string Результирующая строка запроса с замененными аргументами.
     */
    public static function parse(string $query, float $skip, array $args = []): string;
}