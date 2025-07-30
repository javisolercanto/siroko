<?php

declare(strict_types=1);

namespace App\Cart\Application;

use App\Cart\Domain\Entity\Cart;

class CartResponseConverter
{
    public function __invoke(Cart $cart): CartResponse
    {
        return new CartResponse(
            id: $cart->id(),
            owner_id: $cart->ownerId(),
            processed: $cart->processed(),
        );
    }
}
