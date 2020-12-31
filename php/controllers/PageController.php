<?php

namespace app\controllers;

use app\core\Globals\RenderPage;

class PageController {
    public function index() {
        RenderPage::render();
    }

    public function db() {
        RenderPage::render('DatabaseView');
    }
}