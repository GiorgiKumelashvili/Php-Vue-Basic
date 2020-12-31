<?php

use app\controllers\auth\Authentication;
use app\controllers\auth\TokenController;
use app\controllers\Temp;

$routes = [
    "get" => [
        "/" => [Temp::class, 'index'],
    ],
    "post" => [
        // Authentication
        "/auth"=> [Authentication::class, 'index'],

        // Token refresh gets {"data": "refreshToken"}
        "/refreshtoken"=> [TokenController::class, 'refreshAccessToken'],

        // Route for test
        "/1" => [Temp::class, 'index']
    ]
];