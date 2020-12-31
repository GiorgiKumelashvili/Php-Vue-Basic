<?php

namespace app\core\DB;

use PDO;

class Migrations extends DatabaseCore {
    /**
     * DatabaseCore constructor.
     * @param string $dbhost
     * @param string $dbname
     * @param string $dbusername
     * @param string $dbpassword
     */
    public function __construct(string $dbhost, string $dbname, string $dbusername, string $dbpassword) {
        parent::__construct($dbhost, $dbname, $dbusername, $dbpassword);
    }

    public function emptyDB(): void {
        $this->connection()->exec("
            DROP DATABASE {$this->getDbname()};
            CREATE DATABASE {$this->getDbname()};
        ");
    }

    public function createTables(): void {
        /** @noinspection SqlNoDataSourceInspection */
        $this->connection()->exec("
            CREATE TABLE users (
                `id` INT(20) NOT NULL AUTO_INCREMENT,
                `identifier` INT(100) NOT NULL,
                `username` VARCHAR(30) NOT NULL,
                `email` VARCHAR(30) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `active` TINYINT(4) NOT NULL,
                `created_on` TIMESTAMP DEFAULT current_timestamp NOT NULL,
                PRIMARY KEY (id)
            );
        ");
    }

    public function showTables(): array {
        $query = $this->connection()->query('SHOW TABLES');
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }
}