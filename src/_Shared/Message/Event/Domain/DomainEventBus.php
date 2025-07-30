<?php

declare(strict_types=1);

namespace App\_Shared\Message\Event\Domain;

interface DomainEventBus
{
    public function publish(DomainEvent ...$event): void;
}