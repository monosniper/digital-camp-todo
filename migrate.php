<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database\Connection;

$pdo = Connection::getInstance();

$pdo->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
");

$applied = $pdo->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);

$files = glob(__DIR__ . '/src/Database/Migrations/*.php');
sort($files);

foreach ($files as $file) {
    $name = basename($file);

    if (in_array($name, $applied)) {
        continue;
    }

    echo "Running: $name\n";
    require_once $file;

    $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (:migration)");
    $stmt->execute(['migration' => $name]);
}

echo "✅ All migrations complete\n";
