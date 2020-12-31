<?php

namespace app\core\Configs;

class Constants {
    public static function RootPath(string $path = ''): string {
        $root = dirname(__DIR__, 2);
        return "{$root}/{$path}";
    }
}