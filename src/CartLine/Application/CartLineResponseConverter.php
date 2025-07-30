<?php

declare(strict_types=1);

namespace App\CartLine\Application;

use App\CartLine\Domain\Entity\CartLine;

class CartLineResponseConverter
{
    public function __invoke(CartLine $cartLine): CartLineResponse
    {
        return new CartLineResponse(
            id: $cartLine->id(),
            owner_id: $cartLine->ownerId(),
            cart_id: $cartLine->cartId(),
            product_id: $cartLine->productId(),
            quantity: $cartLine->quantity(),
        );
    }
}
