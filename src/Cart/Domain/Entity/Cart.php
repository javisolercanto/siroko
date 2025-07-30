<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\_Shared\Domain\DomainEvents\CartCreatedDomainEvent;
use App\_Shared\Message\AggregateRoot\Entity\AggregateRoot;

class Cart extends AggregateRoot
{
    final public function __construct(
        CartId $id,
        private readonly CartOwnerId $ownerId,
        private readonly CartProcessed $processed,
    ) {
        parent::__construct(id: $id);
    }

    final public static function create(array $primitives): static
    {
        $cart = new static(
            id: CartId::generate(),
            ownerId: new CartOwnerId($primitives['owner_id']),
            processed: new CartProcessed(false),
        );

        $event = new CartCreatedDomainEvent(cartId: $cart->id());
        $cart->record($event);

        return $cart;
    }

    final public static function fromPrimitives(array $primitives): static
    {
        return new static(
            id: new CartId($primitives['id']),
            ownerId: new CartOwnerId($primitives['owner_id']),
            processed: new CartProcessed($primitives['processed']),
        );
    }

    final public function toPrimitives(): array
    {
        return [
            'id' => $this->id(),
            'owner_id' => $this->ownerId(),
            'processed' => $this->processed(),
        ];
    }

    final public function ownerId(): string
    {
        return $this->ownerId->value();
    }

    final public function processed(): bool
    {
        return $this->processed->value();
    }
}
