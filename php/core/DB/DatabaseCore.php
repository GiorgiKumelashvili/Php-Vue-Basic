<?php

namespace app\core\DB;

use PDO;
use PDOException;

class DatabaseCore {
    private string $dbhost;
    private string $dbname;
    private string $dbusername;
    private string $dbpassword;

    private object $connection;

    /**
     * DatabaseCore constructor.
     * @param string $dbhost
     * @param string $dbname
     * @param string $dbusername
     * @param string $dbpassword
     */
    public function __construct(string $dbhost, string $dbname, string $dbusername, string $dbpassword) {
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->dbusername = $dbusername;
        $this->dbpassword = $dbpassword;

        try {
            $db = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbusername, $dbpassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection = $db;
            $this->connection = $db;

            return $db;
        }
        catch (PDOException $e) {
            die("Connection failed: {$e->getMessage()}");
        }
    }

    /**
     * @return PDO object
     */
    public function connection(): object {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getDbname(): string {
        return $this->dbname;
    }
}