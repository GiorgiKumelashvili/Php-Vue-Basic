<?php
/** @noinspection PhpIncludeInspection */

namespace app\core\Globals;

use app\core\Configs\Constants;

class RenderPage {
    public static function render(string $name = 'index') {
        require_once Constants::RootPath("views/{$name}.php");
    }
}