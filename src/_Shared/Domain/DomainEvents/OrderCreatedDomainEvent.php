<?php

declare(strict_types=1);

namespace App\_Shared\Domain\DomainEvents;

use App\_Shared\Message\Event\Domain\DomainEvent;

class OrderCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $orderId,
        public readonly string $cartId,
        public readonly string $ownerId,
    ) {
        parent::__construct(aggregateId: $orderId);
    }
}
