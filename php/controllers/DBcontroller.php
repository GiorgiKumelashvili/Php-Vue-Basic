<?php


namespace app\controllers;


use app\core\Application;

class DBcontroller {
    public function createTables() {
        Application::$app->db->createTables();
    }

    public function emptyDB() {
        Application::$app->db->emptyDB();
    }

    public function showDB() {
        $res = Application::$app->db->showTables();
        echo "<pre>";
        echo "Tables: <br><hr><br>";
        print_r($res);
    }
}