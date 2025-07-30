<?php

declare(strict_types=1);

namespace App\CartLine\Domain\Entity;

use App\_Shared\Domain\DomainEvents\CartLineCreatedDomainEvent;
use App\_Shared\Domain\DomainEvents\CartLineUpdatedDomainEvent;
use App\_Shared\Message\AggregateRoot\Entity\AggregateRoot;

class CartLine extends AggregateRoot
{
    final public function __construct(
        CartLineId $id,
        private readonly CartLineOwnerId $ownerId,
        private readonly CartLineCartId $cartId,
        private readonly CartLineProductId $productId,
        private readonly CartLineQuantity $quantity,
    ) {
        parent::__construct(id: $id);
    }

    final public static function create(array $primitives): static
    {
        $cartLine =  new static(
            id: CartLineId::generate(),
            ownerId: new CartLineOwnerId($primitives['owner_id']),
            cartId: new CartLineCartId($primitives['cart_id']),
            productId: new CartLineProductId($primitives['product_id']),
            quantity: new CartLineQuantity($primitives['quantity']),
        );

        $event = new CartLineCreatedDomainEvent(
            cartLineId: $cartLine->id(),
            product_id: $cartLine->productId(),
            quantity: $cartLine->quantity(),
        );

        $cartLine->record($event);

        return $cartLine;
    }

    /**
     * Overrides new properties into the real object
     * 
     * @param array<string, mixed> $overrides
     */
    final public function update(array $overrides): self
    {
        $cartLine = CartLine::fromPrimitives([
            ...$this->toPrimitives(),
            ...$overrides,
        ]);

        $event = new CartLineUpdatedDomainEvent(
            cartLineId: $cartLine->id(),
            productId: $cartLine->productId(),
            newQuantity: $cartLine->quantity(),
            oldQuantity: $this->quantity(),
        );

        $cartLine->record($event);

        return $cartLine;
    }

    final public static function fromPrimitives(array $primitives): static
    {
        return new static(
            id: new CartLineId($primitives['id']),
            ownerId: new CartLineOwnerId($primitives['owner_id']),
            cartId: new CartLineCartId($primitives['cart_id']),
            productId: new CartLineProductId($primitives['product_id']),
            quantity: new CartLineQuantity($primitives['quantity']),
        );
    }

    final public function toPrimitives(): array
    {
        return [
            'id' => $this->id(),
            'owner_id' => $this->ownerId(),
            'cart_id' => $this->cartId(),
            'product_id' => $this->productId(),
            'quantity' => $this->quantity(),
        ];
    }

    final public function ownerId(): string
    {
        return $this->ownerId->value();
    }

    final public function cartId(): string
    {
        return $this->cartId->value();
    }

    final public function productId(): string
    {
        return $this->productId->value();
    }

    final public function quantity(): int
    {
        return $this->quantity->value();
    }
}
