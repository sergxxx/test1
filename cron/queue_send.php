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
	$queue = $pdo->query('SELECT id, username, email, type FROM queue WHERE status = "N" type = "SEND"')->fetchAll(PDO::FETCH_ASSOC);

    foreach ($queue as $queueItem) {
		$sendResult = send_email($emailFrom, $queueItem['email'], "{$queueItem['username']}, your subscription is expiring soon");
       
        $pdo->prepare('UPDATE queue SET status = :status WHERE id = :id')
			->execute(['status' => 'S', 'id' => $queueItem['id']]);
    }
}

function send_email(string $from, string $to, string $text): bool
{	
	sleep(2);
	return true;
}