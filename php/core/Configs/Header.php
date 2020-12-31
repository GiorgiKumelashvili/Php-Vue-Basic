<?php

namespace app\core\Configs;

class Header {
    public static function SetHeaders(array $params):void {
        foreach ($params as $param) {
            header($param);
        }
    }
}