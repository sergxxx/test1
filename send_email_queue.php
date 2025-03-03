<?php

declare(strict_types=1);

require_once __DIR__ . '/loadEnv.php';

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

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

//выборка пользователей
$users = $pdo->query('
SELECT username, email, valid, checked
FROM users
WHERE
(
   validts BETWEEN UNIX_TIMESTAMP(DATE_ADD(CURDATE(), INTERVAL 1 DAY)) AND UNIX_TIMESTAMP(DATE_ADD(CURDATE(), INTERVAL 1 DAY)) + 86399
   OR validts BETWEEN UNIX_TIMESTAMP(DATE_ADD(CURDATE(), INTERVAL 3 DAY)) AND UNIX_TIMESTAMP(DATE_ADD(CURDATE(), INTERVAL 3 DAY)) + 86399
)
AND confirmed = 1 
AND ((checked = 1 AND valid = 1) OR checked = 0)
')->fetchAll(PDO::FETCH_ASSOC);

//формируем данные для записи в очередь отправки
$prepareDataSend = [];

foreach ($users as $user) {
	if ($user['valid'] === 1) {
        $prepareDataSend[] = "('{$user['username']}', '{$user['email']}', 'SEND')";
    }
}

//пишем в очередь отправки
if (!empty($prepareDataSend)) {
	$pdo->exec('INSERT INTO queue (username, email, type) VALUES ' . implode(', ', $prepareDataSend));
}