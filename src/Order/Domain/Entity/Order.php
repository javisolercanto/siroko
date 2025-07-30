<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\_Shared\Domain\DomainEvents\OrderCreatedDomainEvent;
use App\_Shared\Message\AggregateRoot\Entity\AggregateRoot;

class Order extends AggregateRoot
{
    final public function __construct(
        OrderId $id,
        private readonly OrderOwnerId $ownerId,
        private readonly OrderCartId $cartId,
        private readonly OrderDate $date,
    ) {
        parent::__construct(id: $id);
    }

    final public static function create(array $primitives): static
    {
        $order = new static(
            id: OrderId::generate(),
            ownerId: new OrderOwnerId($primitives['owner_id']),
            cartId: new OrderCartId($primitives['cart_id']),
            date: OrderDate::now(),
        );

        $event = new OrderCreatedDomainEvent(
            orderId: $order->id(),
            ownerId: $order->ownerId(),
            cartId: $order->cartId(),
        );

        $order->record($event);

        return $order;
    }

    final public static function fromPrimitives(array $primitives): static
    {
        return new static(
            id: new OrderId($primitives['id']),
            ownerId: new OrderOwnerId($primitives['owner_id']),
            cartId: new OrderCartId($primitives['cart_id']),
            date: new OrderDate($primitives['date']),
        );
    }

    final public function toPrimitives(): array
    {
        return [
            'id' => $this->id(),
            'owner_id' => $this->ownerId(),
            'cart_id' => $this->cartId(),
            'date' => $this->date(),
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

    final public function date(): \DateTimeInterface
    {
        return $this->date->value();
    }
}
