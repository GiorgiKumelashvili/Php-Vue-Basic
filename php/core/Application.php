<?php

namespace app\core;

use app\core\DB\DB;
use app\core\Routing\Request;
use app\core\Routing\Response;

class Application {
    public static Application $app;
    public array $params;
    public Request $request;
    public Response $response;
    public DB $db;

    public function __construct(array $params) {
        $this->params = $params;

        // Other important objects
        $this->request = new Request();
        $this->response = new Response();

        // Db initialization
        $dbParams = $params['db'];
        $this->db = new DB([
            'dbhost' => $dbParams['dbhost'],
            'dbname' => $dbParams['dbname'],
            'dbusername' => $dbParams['dbusername'],
            'dbpassword' => $dbParams['dbpassword']
        ]);

        // Self Application initialization [For easy access]
        self::$app = $this;
    }
}
