<?php

require_once "../vendor/autoload.php";

//Namespaces
use app\controllers\DBcontroller;
use app\controllers\PageController;
use app\core\Application;
use app\core\Configs\Constants;
use app\core\Routing\Route;
use Dotenv\Dotenv;

// Dot env load
$dotenv = Dotenv::createImmutable(Constants::RootPath());
$dotenv->load();

// Configs later must be transported into core
$configs = [
    'db' => [
        'dbhost' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'dbusername' => $_ENV['DB_USER'],
        'dbpassword' => $_ENV['DB_PASS']
    ]
];

// Main app controller
$app = new Application($configs);

// 'DatabaseView'

Route::get('/', [PageController::class, 'index']);
Route::get('/db', [PageController::class, 'db']);

Route::get('/db', [DBcontroller::class, 'showDb']);

Route::post("/db/createtables", [DBcontroller::class, 'createTables']);
Route::post("/db/emptydb", [DBcontroller::class, 'emptyDB']);

Route::validateUnkownUrl();
