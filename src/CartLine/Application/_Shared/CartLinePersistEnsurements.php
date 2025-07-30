<?php

declare(strict_types=1);

namespace App\CartLine\Application\_Shared;

use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\CartResponse;
use App\Cart\Application\FindByOwnerId\CartFindByOwnerIdQuery;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineOutOfStockException;
use App\Product\Application\IsEnoughStock\ProductIsEnoughStockQuery;

class CartLinePersistEnsurements
{

    public function __construct(
        private readonly QueryBus $queryBus,
    ) {}

    public function __invoke(string $ownerId, string $productId, int $quantity): void
    {
        /** @var CartResponse|null $cart */
        $cart = $this->queryBus->ask(new CartFindByOwnerIdQuery(ownerId: $ownerId));
        if ($cart === null || $cart->processed) {
            throw new CartLineNotFoundException('cart not found');
        }

        $isEnoughStock = $this->queryBus->ask(new ProductIsEnoughStockQuery(
            id: $productId,
            quantity: $quantity,
        ));
        if (!$isEnoughStock) {
            throw new CartLineOutOfStockException();
        }
    }
}
