<?php

declare(strict_types=1);

namespace App\_Shared\Domain\DomainEvents;

use App\_Shared\Message\Event\Domain\DomainEvent;

class UserCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $userId,
    ) {
        parent::__construct(aggregateId: $userId);
    }
}
