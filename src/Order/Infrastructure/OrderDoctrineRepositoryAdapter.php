<?php

declare(strict_types=1);

namespace App\Order\Infrastructure;

use App\_Shared\Infrastructure\RepositoryAdapter;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;
use App\Order\Domain\OrderRepositoryInterface;
use App\Order\Infrastructure\Persistence\OrderOrm;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends RepositoryAdapter<OrderOrm>
 */
class OrderDoctrineRepositoryAdapter extends RepositoryAdapter implements OrderRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, OrderOrm::class);
    }

    public function find(OrderId $id): ?Order
    {
        /** @var OrderOrm|null $persistence */
        $persistence = $this->repository->find($id);
        if ($persistence === null) {
            return null;
        }

        return $this->toDomain($persistence);
    }

    public function findByOwnerId(OrderOwnerId $ownerId): array
    {
        /** @var OrderOrm[] $orders */
        $orders = $this->repository->findBy(['ownerId' => $ownerId]);

        return array_map([$this, 'toDomain'], $orders);
    }

    public function save(Order $order): Order
    {
        $persistence = $this->toPersistence($order);

        $this->entityManager->persist($persistence);
        $this->entityManager->flush();

        return $order;
    }

    /**
     * Transform a OrderOrm object into a Order object.
     *
     * @param OrderOrm $object
     * @return Order
     */
    protected function toDomain(object $object): Order
    {
        return Order::fromPrimitives([
            'id' => $object->id(),
            'owner_id' => $object->ownerId(),
            'cart_id' => $object->cartId(),
            'date' => $object->date(),
        ]);
    }

    /**
     * Transform a Order object into a OrderOrm object.
     *
     * @param Order $object
     * @return OrderOrm
     */
    protected function toPersistence(object $object): OrderOrm
    {
        return new OrderOrm(
            id: $object->id(),
            ownerId: $object->ownerId(),
            date: $object->date(),
            cartId: $object->cartId(),
        );
    }
}
