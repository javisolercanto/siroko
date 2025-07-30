<?php

declare(strict_types=1);

namespace App\_Shared\Message\Event\Domain;

abstract class DomainEvent
{
    public function __construct(
        private string $aggregateId,
    ) {}

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }
}
