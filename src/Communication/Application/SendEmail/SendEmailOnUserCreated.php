<?php

declare(strict_types=1);

namespace App\Communication\Application\SendEmail;

use App\_Shared\Domain\DomainEvents\UserCreatedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class SendEmailOnUserCreated
{
    public function __invoke(UserCreatedDomainEvent $event): void
    {
        // Send an email
    }
}
