<?php

namespace FpDbTest\Interface;

interface DatabaseInterface
{
    /**
     * Строит конечный запрос, заменяя места вставки (?) конечными значениями, а также обрабатывает условные блоки ({...}).
     *
     * @param string $query Исходный запрос.
     * @param array  $args  Аргументы для замены.
     *
     * @return string Конечный запрос.
     */
    public function buildQuery(string $query, array $args = []): string;

    /**
     * Получает значение, указывающее на пропуск условного блока.
     *
     */
    public function skip();
}
