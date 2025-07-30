<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;
use App\Order\Infrastructure\Persistence\OrderOrm;
use App\Order\Infrastructure\OrderDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;

class OrderDoctrineRepositoryAdapterTest extends BaseTestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private EntityRepository&MockObject $repositoryMock;
    private OrderDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(OrderOrm::class)
            ->willReturn($this->repositoryMock);

        $this->repository = new OrderDoctrineRepositoryAdapter($this->entityManager);
    }


    public function testOrderDoctrineRepositoryAdapterFind(): void
    {
        $id = OrderId::generate();
        $ownerId = OrderOwnerId::generate();

        $expected = Order::fromPrimitives([
            'id' => $id->value(),
            'owner_id' => $ownerId->value(),
            'cart_id' => '019852d0-c479-7eec-819f-0f30942a48a6',
            'date' => new \DateTimeImmutable(),
        ]);

        $expectedOrm = new OrderOrm(
            id: $expected->id(),
            ownerId: $expected->ownerId(),
            cartId: $expected->cartId(),
            date: $expected->date(),
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('findBy')
            ->with(['ownerId' => $ownerId->value()])
            ->willReturn([$expectedOrm]);

        $order = $this->repository->findByOwnerId(ownerId: $ownerId);

        $this->assertEquals([$expected], $order);
    }

    public function testOrderDoctrineRepositoryAdapterFindNotFound(): void
    {
        $ownerId = OrderOwnerId::generate();

        $this->repositoryMock
            ->expects($this->once())
            ->method('findBy')
            ->with(['ownerId' => $ownerId->value()])
            ->willReturn([]);

        $order = $this->repository->findByOwnerId(ownerId: $ownerId);

        $this->assertEmpty($order);
    }

    public function testOrderDoctrineRepositoryAdapterSave(): void
    {
        $order = Order::create([
            'owner_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'cart_id' => '019852d0-c479-7eec-819f-0f30942a48a6',
            'date' => new \DateTimeImmutable(),
        ]);

        $expectedOrm = new OrderOrm(
            id: $order->id(),
            ownerId: $order->ownerId(),
            cartId: $order->cartId(),
            date: $order->date(),
        );

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (OrderOrm $actual) use ($expectedOrm) {
                return $actual->id() === $expectedOrm->id() &&
                    $actual->ownerId() === $expectedOrm->ownerId();
            }));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->repository->save($order);

        $this->assertSame($order, $result);
    }
}
