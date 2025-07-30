<?php

declare(strict_types=1);

namespace App\Cart\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartOwnerId;

class CartApplicationService implements CartRepositoryInterface
{
    public function __construct(
        private readonly DomainEventBus $eventBus,
        private readonly CartRepositoryInterface $repository,
    ) {}

    public function save(Cart $cart): Cart
    {
        $this->repository->save($cart);
        $cart->dispatchEvents($this->eventBus);

        return $cart;
    }

    public function update(Cart $cart): Cart
    {
        return $this->repository->update($cart);
    }

    public function find(CartId $id): ?Cart
    {
        return $this->repository->find($id);
    }

    public function findByOwnerId(CartOwnerId $ownerId): ?Cart
    {
        return $this->repository->findByOwnerId($ownerId);
    }
}
