<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\UserRegistered;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class SendEmailOnUserRegisteredHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(UserRegistered $message): void
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to($message->email)
            ->subject('Hi!')
            ->text('Thanks for registration!');

        $this->mailer->send($email);
    }
}
