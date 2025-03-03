<?php
function loadEnv($envPath)
{
    if (!file_exists($envPath)) {
        die('Error');
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
	foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
			 continue;
		}
		
		list($key, $value) = explode('=', $line, 2);
		
        $_ENV[$key] = $value;
    }
}

loadEnv(__DIR__ . '/.env');