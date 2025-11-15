<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserRegistered;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class WriteLogOnUserRegisteredHandler
{
    public function __invoke(UserRegistered $message): void
    {
        error_log("New user created: {$message->email} (ID: {$message->userId})");
    }
}
