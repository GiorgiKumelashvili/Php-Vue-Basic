<?php

namespace app\core\Configs;

class Path {
    public static function location(string $path): string {
        $ROOT = Constants::RootPath();
        return "{$ROOT}{$path}.php";
    }
}