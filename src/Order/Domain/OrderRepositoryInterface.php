<?php

declare(strict_types=1);

namespace App\Order\Domain;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;

interface OrderRepositoryInterface
{
    /**
     * Find a order by id
     * 
     * @param OrderId $id
     * @return Order|null
     */
    public function find(OrderId $id): ?Order;

    /**
     * Find a order by owner id
     * 
     * @param OrderOwnerId $ownerId
     * @return Order[]
     */
    public function findByOwnerId(OrderOwnerId $ownerId): array;

    /**
     * Create a new order
     * 
     * @param Order $order
     * @return Order
     */
    public function save(Order $order): Order;
}
