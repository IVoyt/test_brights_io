<?php

use app\base\Application;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/helpers.php';

$application = Application::getInstance();
$dbConfig    = $application->getDbConfig();

$pdo = new PDO(
    "{$dbConfig['connection']}:host={$dbConfig['host']};port=9906;dbname={$dbConfig['database']}",
    $dbConfig['username'],
    $dbConfig['password']
);

$query = 'CREATE TABLE IF NOT EXISTS list_contact (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email_address VARCHAR(254) NOT NULL UNIQUE,
    name VARCHAR(200) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);';
$pdo->query($query);



