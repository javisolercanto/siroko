<?php

declare(strict_types=1);

namespace App\_Shared\Message\Command\Infrastructure;

use App\_Shared\Message\Command\Domain\Command;
use App\_Shared\Message\Command\Domain\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerCommandBus implements CommandBus
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    public function dispatch(Command $command): void
    {
        $this->bus->dispatch($command);
    }
}
