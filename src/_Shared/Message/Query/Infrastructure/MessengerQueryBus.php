<?php

declare(strict_types=1);

namespace App\_Shared\Message\Query\Infrastructure;

use App\_Shared\Message\Query\Domain\Query;
use App\_Shared\Message\Query\Domain\QueryBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerQueryBus implements QueryBus
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    public function ask(Query $query): mixed
    {
        /** @var Envelope $envelope */
        $envelope = $this->bus->dispatch($query);

        /** @var HandledStamp|null $stamp */
        $stamp = $envelope->last(HandledStamp::class);

        return $stamp?->getResult();
    }
}
