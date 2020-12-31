<?php

namespace app\core\Routing;

class Request {
    public function getUrl(): string {
        $currentUrl = isset($_GET['url']) ? "/{$_GET['url']}" : "/";

        if (strlen($currentUrl) !== 1 && substr($currentUrl, -1) === '/')
            $currentUrl = rtrim($currentUrl, '/');

        return $currentUrl;
    }

    public function method(): string {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): bool {
        return $this->method() === 'get';
    }

    public function isPost(): bool {
        return $this->method() === 'post';
    }
}