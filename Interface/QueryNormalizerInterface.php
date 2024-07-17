<?php

namespace FpDbTest\Interface;

interface QueryNormalizerInterface
{
    /**
     * Нормализует SQL запрос, заменяя строковые литералы на символы подстановки и добавляя их в массив аргументов.
     *
     * @param string $query SQL запрос.
     * @param array  $args  Аргументы подстановки.
     *
     * @return array Нормализованный SQL запрос и обновленный массив аргументов.
     */
    public static function normalize(string $query, array $args): array;
}