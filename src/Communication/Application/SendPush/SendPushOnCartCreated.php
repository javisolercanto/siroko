<?php

declare(strict_types=1);

namespace App\Communication\Application\SendPush;

use App\_Shared\Domain\DomainEvents\CartCreatedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class SendPushOnCartCreated
{
    public function __invoke(CartCreatedDomainEvent $event): void
    {
        // Send a push
    }
}
