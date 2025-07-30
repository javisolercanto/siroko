<?php

declare(strict_types=1);

namespace App\_Shared\Domain\DomainEvents;

use App\_Shared\Message\Event\Domain\DomainEvent;

class CartLineCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $cartLineId,
        public readonly string $product_id,
        public readonly int $quantity,
    ) {
        parent::__construct(aggregateId: $cartLineId);
    }
}
