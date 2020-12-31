<?php
/**
 * @author Giorgi Kumelashvili
 * Date : 12/25/2020
 * Time : 20:55
 * (Current Time)
 *
 * Backend service with php
 * includes admin panel which is
 * accessible only by db
 *
 *
 * @noinspection PhpIncludeInspection
 *
 */

// Namespaces
use app\core\Application;
use app\core\Configs\Constants;
use app\core\Configs\Header;
use app\core\Configs\Path;
use app\core\Routing\Api;
use Dotenv\Dotenv;

// Requires php files
require_once "../vendor/autoload.php";                  // Auto loader
require_once Path::location('api/routes/routes');   // Routes

// Dot env load
$dotenv = Dotenv::createImmutable(Constants::RootPath())->load();

// Set Headers
Header::SetHeaders([
    "Access-Control-Allow-Origin: {$_ENV['FRONT_ACCESS_LOCATION']}",
    "Access-Control-Allow-Headers: *"
]);

// Configs
$configs = [
    'db' => [
        'dbhost' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'dbusername' => $_ENV['DB_USER'],
        'dbpassword' => $_ENV['DB_PASS']
    ]
];

// Initialize main app controller
$app = new Application($configs);

// Call routes according to method type
foreach ($routes as $methodType => $vals) {
    foreach ($routes[$methodType] as $route => $arrr) {
        call_user_func_array("\app\core\Routing\Api::{$methodType}", [$route, $arrr]);
    }
}

// Validate Urls
Api::validateUnkownUrl();
