<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/basics/echo_bot.php';

use Phenogram\Bindings\Api;
use Phenogram\Bindings\ClientInterface;
use Phenogram\Bindings\Types\Chat;
use Phenogram\Bindings\Types\Interfaces\ResponseInterface;
use Phenogram\Bindings\Types\Message;
use Phenogram\Bindings\Types\Response;
use Phenogram\Bindings\Types\Update;
use Phenogram\Framework\TelegramBot;
use Psr\Log\NullLogger;

use function Amp\Future\await;

$client = new class implements ClientInterface {
    /** @var list<array{method: string, data: array<mixed>}> */
    public array $requests = [];

    public function sendRequest(string $method, array $data): ResponseInterface
    {
        $this->requests[] = [
            'method' => $method,
            'data' => $data,
        ];

        return new Response(
            ok: true,
            result: [
                'message_id' => 2,
                'date' => 1_700_000_001,
                'chat' => [
                    'id' => 1001,
                    'type' => 'private',
                ],
                'text' => $data['text'],
            ],
        );
    }
};

$bot = createEchoBot(
    token: 'offline-test-token',
    api: new Api($client),
    logger: new NullLogger(),
);

if (!$bot instanceof TelegramBot) {
    throw new RuntimeException('The echo example did not create a TelegramBot instance.');
}

$update = new Update(
    updateId: 1,
    message: new Message(
        messageId: 1,
        date: 1_700_000_000,
        chat: new Chat(id: 1001, type: 'private'),
        text: 'Hello from the offline test.',
    ),
);

await($bot->handleUpdate($update));

if (count($client->requests) !== 1) {
    throw new RuntimeException('The echo example did not send exactly one message.');
}

if ($client->requests[0]['method'] !== 'sendMessage') {
    throw new RuntimeException('The echo example called an unexpected API method.');
}

if ($client->requests[0]['data']['chat_id'] !== 1001) {
    throw new RuntimeException('The echo example used an unexpected chat identifier.');
}

if ($client->requests[0]['data']['text'] !== 'Hello from the offline test.') {
    throw new RuntimeException('The echo example changed the message text.');
}

putenv('TELEGRAM_BOT_TOKEN=process-environment-token');

try {
    $config = require __DIR__ . '/../config/config.php';
    if ($config['token'] !== 'process-environment-token') {
        throw new RuntimeException('The process environment token did not take precedence.');
    }
} finally {
    putenv('TELEGRAM_BOT_TOKEN');
}

echo "Example smoke test passed.\n";
