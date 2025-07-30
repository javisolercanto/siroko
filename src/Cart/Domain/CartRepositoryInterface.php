<?php

declare(strict_types=1);

namespace App\Cart\Domain;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\Entity\CartOwnerId;

interface CartRepositoryInterface
{
    /**
     * Find a cart by id
     * 
     * @param CartId $id
     * @return ?Cart
     */
    public function find(CartId $id): ?Cart;

    /**
     * Find a cart by owner id
     * 
     * @param CartOwnerId $ownerId
     * @return ?Cart
     */
    public function findByOwnerId(CartOwnerId $ownerId): ?Cart;

    /**
     * Create a new cart
     * 
     * @param Cart $cart
     * @return Cart
     */
    public function save(Cart $cart): Cart;

    /**
     * Update a cart
     * 
     * @param Cart $cart
     * @return Cart
     */
    public function update(Cart $cart): Cart;
}
