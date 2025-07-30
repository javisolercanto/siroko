<?php

declare(strict_types=1);

namespace App\CartLine\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineCartId;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Domain\Entity\CartLineProductId;

class CartLineApplicationService implements CartLineRepositoryInterface
{
    public function __construct(
        private readonly DomainEventBus $eventBus,
        private readonly CartLineRepositoryInterface $repository,
    ) {}

    public function find(CartLineId $id): ?CartLine
    {
        return $this->repository->find($id);
    }

    /**
     * @return CartLine[]
     */
    public function findByCartId(CartLineCartId $cartId): array
    {
        return $this->repository->findByCartId($cartId);
    }

    /**
     * @return CartLine|null
     */
    public function findByProductId(CartLineCartId $cartId, CartLineProductId $productId): ?CartLine
    {
        return $this->repository->findByProductId($cartId, $productId);
    }

    public function save(CartLine $cartLine): CartLine
    {
        $this->repository->save($cartLine);
        $cartLine->dispatchEvents($this->eventBus);

        return $cartLine;
    }

    public function update(CartLine $cartLine): CartLine
    {
        $this->repository->update($cartLine);
        $cartLine->dispatchEvents($this->eventBus);

        return $cartLine;
    }

    public function delete(CartLine $cartLine): void
    {
        $this->repository->delete($cartLine);
        $cartLine->dispatchEvents($this->eventBus);
    }
}
