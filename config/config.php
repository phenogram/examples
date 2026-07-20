<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

$processBotToken = getenv('TELEGRAM_BOT_TOKEN');
$botToken = is_string($processBotToken)
    ? $processBotToken
    : ($_SERVER['TELEGRAM_BOT_TOKEN'] ?? $_ENV['TELEGRAM_BOT_TOKEN'] ?? null);

if (!is_string($botToken) || trim($botToken) === '') {
    throw new RuntimeException(
        'Set TELEGRAM_BOT_TOKEN in the environment or in a local .env file.',
    );
}

return [
    'token' => $botToken,
];
