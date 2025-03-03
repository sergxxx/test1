<?php

declare(strict_types=1);

require_once __DIR__ . '/loadEnv.php';

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$migrationsPath = __DIR__ . '/' . ($_ENV['MIGRATIONS_PATH']);

try {
    $pdo = new PDO(
		"mysql:host=$host;dbname=$dbname;charset=utf8",
		$username,
		$password, 
		[
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		]
	);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}

$migrationFiles = glob($migrationsPath . '/*.sql');

if (empty($migrationFiles)) {
    die('Fiiles not found' . PHP_EOL);
}

foreach ($migrationFiles as $file) {
    $filename = basename($file);
    $sql = file_get_contents($file);

    try {
        $pdo->exec($sql);
    } catch (PDOException $e) {
    }
}