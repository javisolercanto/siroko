<?php

declare(strict_types=1);

namespace App\_Shared\Domain\DomainEvents;

use App\_Shared\Message\Event\Domain\DomainEvent;

class CartCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $cartId,
    ) {
        parent::__construct(aggregateId: $cartId);
    }
}
