# Описание проекта

Сервис для рассылки уведомлений об истекающих подписках.

---

## Установка и настройка

### 1. Клонирование репозитория

```sh
git git@github.com:sergxxx/test1.git
```

### 2. Настройка переменных

Создайте файл `.env` в корневой директории и укажите параметры:

```ini
DB_HOST=your_database_host
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASSWORD=your_database_password
MIGRATIONS_PATH=migrations
EMAIL_FROM=your_email@example.com
```

### 3. Запуск скриптов

Запуск миграций:
```sh
php migrate.php
```

Запуск обработки очереди проверки email на валидность:
```sh
php cron/queue_check.php
```

Запуск обработки очереди на отправку email:
```sh
php cron/queue_send.php
```

Этот скрипт подключается к базе данных и выполняет все SQL-скрипты, содержащиеся в папке миграций.

---

## Описание скриптов

### `loadEnv.php`:
Загружает переменные окружения из `.env` файла.

### `migrate.php`:
Запускает миграции базы данных папки`MIGRATIONS_PATH`.

### `check_email_queue.php`:
Выбирает пользователей, чья подписка истекает через 4 дня, и добавляет их в очередь проверки email.

### `send_email_queue.php`:
Выбирает пользователей, у которых подписка истекает через 1 или 3 дня, и добавляет их в очередь на отправку уведомлений.

### `cron/queue_check.php`:
Проверяет email из очереди.

### `cron/queue_send.php`:
Отсылает email из очереди.
