<?php

// Загружаем bootstrap автозагрузки для доступа к классам
require __DIR__ . '/vendor/autoload.php';

echo "==== Диагностика переменных окружения ====\n\n";

// Загружаем .env файл напрямую
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    echo "✓ .env файл успешно загружен\n";
} catch (\Exception $e) {
    echo "✗ Ошибка загрузки .env файла: " . $e->getMessage() . "\n";
}

// Проверка прав доступа и наличия файла
echo "\n== Проверка файла .env ==\n";
if (file_exists(__DIR__ . '/.env')) {
    echo "✓ Файл .env существует\n";
    echo "✓ Размер файла: " . filesize(__DIR__ . '/.env') . " байт\n";
    echo "✓ Права доступа: " . substr(sprintf('%o', fileperms(__DIR__ . '/.env')), -4) . "\n";
    echo "✓ Доступен для чтения: " . (is_readable(__DIR__ . '/.env') ? "Да" : "Нет") . "\n";
} else {
    echo "✗ Файл .env не найден!\n";
}

// Проверка значений переменных
echo "\n== Значения переменных окружения ==\n";
echo "APP_NAME: " . ($_ENV['APP_NAME'] ?? 'не задано') . "\n";
echo "APP_URL: " . ($_ENV['APP_URL'] ?? 'не задано') . "\n";

// Проверка переменных Telegram
echo "\n== Переменные Telegram ==\n";
echo "TELEGRAM_BOT_TOKEN: " . (isset($_ENV['TELEGRAM_BOT_TOKEN']) ? "задано (" . strlen($_ENV['TELEGRAM_BOT_TOKEN']) . " символов)" : "не задано") . "\n";
echo "TELEGRAM_CHANNEL_ID: " . ($_ENV['TELEGRAM_CHANNEL_ID'] ?? 'не задано') . "\n";

// Проверка значений через env() функцию Laravel
echo "\n== Значения через функцию env() ==\n";
echo "env('TELEGRAM_BOT_TOKEN'): " . (env('TELEGRAM_BOT_TOKEN') ? "задано (" . strlen(env('TELEGRAM_BOT_TOKEN')) . " символов)" : "не задано") . "\n";
echo "env('TELEGRAM_CHANNEL_ID'): " . (env('TELEGRAM_CHANNEL_ID') ?? 'не задано') . "\n";

// Сравнение
echo "\n== Тест сервиса Telegram ==\n";
$token = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;
$channel = $_ENV['TELEGRAM_CHANNEL_ID'] ?? null;

if ($token && $channel) {
    echo "✓ Переменные установлены, пробуем отправить тестовое сообщение...\n";
    
    // Отправка тестового сообщения
    $apiUrl = "https://api.telegram.org/bot{$token}/sendMessage";
    
    $data = [
        'chat_id' => $channel,
        'text' => 'Тестовое сообщение от диагностики ' . date('Y-m-d H:i:s'),
        'parse_mode' => 'Markdown'
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);
    
    if ($result === FALSE) {
        echo "✗ Ошибка отправки сообщения\n";
    } else {
        echo "✓ Сообщение успешно отправлено\n";
        echo "Ответ API: " . $result . "\n";
    }
} else {
    echo "✗ Невозможно выполнить тест: одна или обе переменные не установлены\n";
}

echo "\n=== Конец диагностики ===\n";
