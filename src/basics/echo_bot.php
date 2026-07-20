<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Phenogram\Bindings\Types\Interfaces\UpdateInterface;
use Phenogram\Bindings\ApiInterface;
use Phenogram\Framework\TelegramBot;
use Psr\Log\LoggerInterface;

function createEchoBot(
    string $token,
    ?ApiInterface $api = null,
    ?LoggerInterface $logger = null,
): TelegramBot
{
    $bot = new TelegramBot(
        token: $token,
        api: $api,
        logger: $logger,
    );
    $bot
        ->addHandler(static function (UpdateInterface $update, TelegramBot $bot): void {
            $message = $update->message;

            if ($message?->text === null) {
                return;
            }

            $bot->api->sendMessage(
                chatId: $message->chat->id,
                text: $message->text,
            );
        })
        ->supports(static fn (UpdateInterface $update): bool => $update->message?->text !== null);

    return $bot;
}

if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    ['token' => $token] = require __DIR__ . '/../../config/config.php';

    createEchoBot($token)->run();
}
