<?php

namespace app\base;

use PDO;

abstract class Migration
{
    protected PDO $pdo;

    public function __construct()
    {
        $dbConfig  = Application::getInstance()->getDbConfig();
        $this->pdo = new PDO(
            "{$dbConfig['connection']}:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['database']}",
            $dbConfig['username'],
            $dbConfig['password']
        );
    }

    abstract public function run(): void;
}
