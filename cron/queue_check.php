<?php

declare(strict_types=1);

require_once __DIR__ . '/../loadEnv.php';

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$emailFrom = $_ENV['EMAIL_FROM'];

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

while (true) {
	//выборка из очереди
	$queue = $pdo->query('SELECT id, username, email, type FROM queue WHERE status = "N" AND type = "CHECK" LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);

    foreach ($queue as $queueItem) {
        $checkResult = check_email($queueItem['email']);
		
		//обновляем очередь
        $pdo->prepare('UPDATE queue SET status = :status WHERE id = :id')
			->execute(['status' => 'S', 'id' => $queueItem['id']]);
		
		//обновляем статус проверки пользователей
		$pdo->prepare('UPDATE users SET checked = :checked, valid = :valid WHERE email = :email')
			->execute(['email' => $queueItem['email'], 'checked' => 1, 'valid' => $checkResult ? 1 : 0]);

    }
}

function check_email(string $from): bool
{
	sleep(3);
	return false;
}