<?php

namespace app\core\DB;

class DB extends Migrations {
    private const keys = ['dbhost', 'dbname', 'dbusername', 'dbpassword'];

    public function __construct(array $params) {
        $this->checkParams($params);
        parent::__construct($params['dbhost'], $params['dbname'], $params['dbusername'], $params['dbpassword']);
    }

    private function checkParams(array $params): void {
        if (count($params) !== 4)
            die("Parameters must contain exactly 4 argument");

        foreach (DB::keys as $key) {
            if (!array_key_exists($key, $params)) {
                die("Incorrect key, missing [{$key}]");
            }
        }
    }
}