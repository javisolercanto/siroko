<?php

declare(strict_types=1);

namespace App\Order\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;

class OrderApplicationService implements OrderRepositoryInterface
{
    public function __construct(
        private readonly DomainEventBus $eventBus,
        private readonly OrderRepositoryInterface $repository,
    ) {}

    public function find(OrderId $id): ?Order
    {
        return $this->repository->find($id);
    }

    public function save(Order $order): Order
    {
        $this->repository->save($order);
        $order->dispatchEvents($this->eventBus);

        return $order;
    }

    /**
     * @return Order[]
     */
    public function findByOwnerId(OrderOwnerId $ownerId): array
    {
        return $this->repository->findByOwnerId($ownerId);
    }
}
