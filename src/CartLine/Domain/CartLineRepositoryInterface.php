<?php

declare(strict_types=1);

namespace App\CartLine\Domain;

use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineCartId;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Domain\Entity\CartLineProductId;

interface CartLineRepositoryInterface
{
    /**
     * Find a cart line by id
     * 
     * @param CartLineId $id
     * @return CartLine
     */
    public function find(CartLineId $id): ?CartLine;

    /**
     * Find all cart lines by cart id
     * 
     * @param CartLineCartId $cartId
     * @return CartLine[]
     */
    public function findByCartId(CartLineCartId $cartId): array;

    /**
     * Find a cart line by product id
     * 
     * @param CartLineCartId $cartId
     * @param CartLineProductId $productId
     * @return CartLine|null
     */
    public function findByProductId(CartLineCartId $cartId, CartLineProductId $productId): ?CartLine;

    /**
     * Create a new cart line
     * 
     * @param CartLine $cartLine
     * @return CartLine
     */
    public function save(CartLine $cartLine): CartLine;

    /**
     * Update a cart line
     * 
     * @param CartLine $cartLine
     * @return CartLine
     */
    public function update(CartLine $cartLine): CartLine;

    /**
     * Delete a cart line
     * 
     * @param CartLine $cartLine
     */
    public function delete(CartLine $cartLine): void;
}
