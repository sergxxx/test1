CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    validts BIGINT NOT NULL DEFAULT 0,
    confirmed TINYINT(1) NOT NULL DEFAULT 0,
    checked TINYINT(1) NOT NULL DEFAULT 0,
    valid TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;