**English** · [Русский](README.md)

# Phenogram Framework examples

This repository contains small applications for the current stable Phenogram Framework.

## Requirements

- PHP 8.4 or later
- Composer 2
- A Telegram bot token from [BotFather](https://t.me/BotFather)

## Compatibility

| Component | Version |
| --- | --- |
| Phenogram Framework | `^6.0` |
| Phenogram Bindings | `^7.0` |
| Telegram Bot API model | 9.6 |

The example imports Bindings types directly.
The project therefore declares Bindings as a direct dependency.

## Install

Install the dependencies:

```bash
composer install
```

Create a local environment file:

```bash
cp .env.dist .env
```

Open `.env`. Replace the placeholder with your bot token. Do not commit this file.

## Run the echo bot

Start the example:

```bash
php src/basics/echo_bot.php
```

Send a text message to the bot. The bot sends the same text to the chat. Press `Ctrl+C` to stop the process.

The example uses long polling. It makes live requests to the Telegram Bot API.

## Verify the repository

Run the offline checks:

```bash
composer check
```

The check validates Composer metadata.
It runs the complete echo flow with a local API client.
It does not use a bot token or a network request.
