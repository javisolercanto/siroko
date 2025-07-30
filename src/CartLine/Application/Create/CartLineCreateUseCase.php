<?php

declare(strict_types=1);

namespace App\CartLine\Application\Create;

use App\_Shared\Message\Query\Domain\QueryBus;
use App\CartLine\Application\_Shared\CartLinePersistEnsurements;
use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineCartId;
use App\CartLine\Domain\Entity\CartLineProductId;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineOutOfStockException;
use App\Product\Application\Find\ProductFindQuery;
use App\Product\Application\IsEnoughStock\ProductIsEnoughStockQuery;
use App\Product\Application\ProductResponse;

class CartLineCreateUseCase
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CartLinePersistEnsurements $ensurements,
        private readonly CartLineApplicationService $applicationService,
    ) {}

    public function __invoke(CartLineCreateCommand $command): void
    {
        /** @var ProductResponse|null $product */
        $product = $this->queryBus->ask(new ProductFindQuery(id: $command->productId));
        if ($product === null) {
            throw new CartLineNotFoundException('product not found');
        }

        $this->ensurements->__invoke(
            ownerId: $command->ownerId,
            productId: $product->id,
            quantity: $command->quantity,
        );

        /** @var CartLine|null $existingCartLine */
        $existingCartLine = $this->applicationService->findByProductId(
            productId: new CartLineProductId($command->productId),
            cartId: new CartLineCartId($command->cartId),
        );

        if ($existingCartLine !== null) {
            $cartLine = $existingCartLine->update([
                'quantity' => $existingCartLine->quantity() + $command->quantity
            ]);

            $this->applicationService->update($cartLine);
            return;
        }

        $cartLine = CartLine::create($command->toPrimitives());
        $this->applicationService->save($cartLine);
    }
}
