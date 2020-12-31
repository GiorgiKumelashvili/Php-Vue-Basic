<?php

namespace app\core\Routing;

use app\controllers\auth\TokenController;
use app\core\Abstracts\Routing;
use app\core\Application;

class Api extends Routing {
    protected static array $routes = [];
    protected static string $className;
    protected static string $methodName;
    protected const StatusCode = 404;

    public static function get(string $path, array $params): void {
        self::validateParameters($params);
        self::$className = $params[0];
        self::$methodName = $params[1];
        self::$routes['get'][$path] = $params;

        if (self::validateUrl($path) && Application::$app->request->isGet())
            self::execArray();
    }

    public static function post(string $path, array $params): void {
        self::validateParameters($params);
        self::$className = $params[0];
        self::$methodName = $params[1];
        self::$routes['post'][$path] = $params;

        // validate post with token
        if (Application::$app->request->isPost()) {
            $arr = explode('/', $_SERVER['REQUEST_URI']);
            $currentRoute = $arr[array_key_last($arr)];

            if ($currentRoute !== 'auth' && $currentRoute !== 'refreshtoken') {
                TokenController::validateAccessToken();
            }
        }

        if (self::validateUrl($path) && Application::$app->request->isPost())
            self::execArray();
    }

    private static function execArray(): void {
        if (method_exists(self::$className, self::$methodName)) {
            call_user_func([new self::$className(), self::$methodName]);
        }
        else {
            $methodName = self::$methodName;
            self::throwException("<br>\"{$methodName}()\" method doesn't exists<br>");
        }
    }

    public static function getRoutes($method): array {
        return self::$routes[$method];
    }

    public static function throwException($message): void {
        http_response_code(self::StatusCode);
        Application::$app->response->sendResponse('error', $message);
    }

    public static function validateParameters(array $params): void {
        if (count($params) !== 2)
            self::throwException("Parameters must contain exactly 2 argument");

        if (gettype($params[0]) !== 'string' || gettype($params[1]) !== 'string')
            self::throwException("Arguments must be both string type");
    }

    public static function validateUrl($requestedPath): bool {
        $currentUrl = Application::$app->request->getUrl();

        if (strlen($currentUrl) !== 1 && substr($currentUrl, -1) === '/')
            $currentUrl = rtrim($currentUrl, '/');

        return $currentUrl === $requestedPath;
    }

    public static function validateUnkownUrl() {
        $currentUrl = Application::$app->request->getUrl();

        // Error Pgae not found validation
        if (
            Application::$app->request->isGet() &&
            !array_key_exists($currentUrl, self::getRoutes('get'))
        ) {
            self::throwException("Get page Not found");
        }

        if (
            Application::$app->request->isPost() &&
            !array_key_exists($currentUrl, self::getRoutes('post'))
        ) {
            self::throwException("Post page Not found");
        }
    }
}