<?php

declare(strict_types=1);

namespace App\_Shared\Message\Event\Infrastructure;

use App\_Shared\Message\Event\Domain\DomainEvent;
use App\_Shared\Message\Event\Domain\DomainEventBus;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerDomainEventBus implements DomainEventBus
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    public function publish(DomainEvent ...$event): void
    {
        foreach ($event as $domainEvent) {
            $this->bus->dispatch($domainEvent);
        }
    }
}
