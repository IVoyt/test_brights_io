<?php

use app\base\Migration;

class InitialMigration extends Migration
{
    public function run(): void
    {
        $query = 'CREATE TABLE IF NOT EXISTS list_contact (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            email_address VARCHAR(254) NOT NULL UNIQUE,
            name VARCHAR(200) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );';
        $this->pdo->query($query);
    }
}



