<?php

declare(strict_types=1);

namespace App\_Shared\Domain\DomainEvents;

use App\_Shared\Message\Event\Domain\DomainEvent;

class CartLineUpdatedDomainEvent extends DomainEvent
{
    public function __construct(
        string $cartLineId,
        public readonly string $productId,
        public readonly int $newQuantity,
        public readonly int $oldQuantity,
    ) {
        parent::__construct(aggregateId: $cartLineId);
    }
}
