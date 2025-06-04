<?php

use App\Database\Connection;

$pdo = Connection::getInstance();

$pdo->exec("
    CREATE TABLE IF NOT EXISTS todos (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT NULL,
        deadline TIMESTAMP NULL,
        is_done TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");
