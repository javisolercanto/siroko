<?php

declare(strict_types=1);

namespace App\_Shared\Domain\DomainEvents;

use App\_Shared\Message\Event\Domain\DomainEvent;

class CartLineDeletedDomainEvent extends DomainEvent
{
    public function __construct(
        string $cartLineId,
        public readonly string $productId,
        public readonly int $quantity,
    ) {
        parent::__construct(aggregateId: $cartLineId);
    }
}
