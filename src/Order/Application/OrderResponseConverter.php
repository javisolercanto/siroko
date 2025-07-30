<?php

declare(strict_types=1);

namespace App\Order\Application;

use App\Order\Domain\Entity\Order;

class OrderResponseConverter
{
    public function __invoke(Order $order): OrderResponse
    {
        return new OrderResponse(
            id: $order->id(),
            owner_id: $order->ownerId(),
            cart_id: $order->cartId(),
            date: $order->date()->format('c'),
        );
    }
}
