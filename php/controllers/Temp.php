<?php

namespace app\controllers;

class Temp {
    public function index() {
        header('Content-Type: application/json');
        $arr= [
            'x' => 123
        ];
        echo json_encode($arr);
    }
}