<?php

namespace app\core\Abstracts;

abstract class Routing {
    abstract public static function get(string $path, array $params): void;
    abstract public static function post(string $path, array $params): void;
}