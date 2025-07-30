<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure;

use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Infrastructure\Persistence\CartLineOrm;
use App\CartLine\Infrastructure\CartLineDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;

class CartLineDoctrineRepositoryAdapterTest extends BaseTestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private EntityRepository&MockObject $repositoryMock;
    private CartLineDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(CartLineOrm::class)
            ->willReturn($this->repositoryMock);

        $this->repository = new CartLineDoctrineRepositoryAdapter($this->entityManager);
    }


    public function testCartLineDoctrineRepositoryAdapterFind(): void
    {
        $id = CartLineId::generate();

        $expected = CartLine::fromPrimitives([
            'id' => $id->value(),
            'owner_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'cart_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'product_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'quantity' => 1,
        ]);

        $expectedOrm = new CartLineOrm(
            id: $expected->id(),
            ownerId: $expected->ownerId(),
            cartId: $expected->cartId(),
            productId: $expected->productId(),
            quantity: $expected->quantity()
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn($expectedOrm);

        $cartline = $this->repository->find($id);

        $this->assertEquals($expected, $cartline);
    }

    public function testCartLineDoctrineRepositoryAdapterFindNotFound(): void
    {
        $id = CartLineId::generate();

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn(null);

        $cartline = $this->repository->find($id);

        $this->assertNull($cartline);
    }

    public function testCartLineDoctrineRepositoryAdapterSave(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'cart_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'product_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'quantity' => 1,
        ]);

        $expectedOrm = new CartLineOrm(
            id: $cartline->id(),
            ownerId: $cartline->ownerId(),
            cartId: $cartline->cartId(),
            productId: $cartline->productId(),
            quantity: $cartline->quantity(),
        );

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (CartLineOrm $actual) use ($expectedOrm) {
                return $actual->id() === $expectedOrm->id() &&
                    $actual->ownerId() === $expectedOrm->ownerId();
            }));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->repository->save($cartline);

        $this->assertSame($cartline, $result);
    }

    public function testCartLineDoctrineRepositoryAdapterDelete(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '0198521d-7868-7d8f-9737-9789a7e1604c',
            'cart_id' => '0198521d-7868-7d8f-9737-9789a7e1604c',
            'product_id' => '0198521d-7868-7d8f-9737-9789a7e1604c',
            'quantity' => 1,
        ]);

        $expectedOrm = new CartLineOrm(
            id: $cartline->id(),
            ownerId: $cartline->ownerId(),
            cartId: $cartline->cartId(),
            productId: $cartline->productId(),
            quantity: $cartline->quantity(),
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($cartline->id())
            ->willReturn($expectedOrm);

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($expectedOrm);

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository->delete($cartline);
    }

    public function testCartLineDoctrineRepositoryAdapterDeleteError(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '0198521d-7868-7d8f-9737-9789a7e1604d',
            'cart_id' => '0198521d-7868-7d8f-9737-9789a7e1604d',
            'product_id' => '0198521d-7868-7d8f-9737-9789a7e1604d',
            'quantity' => 1,
        ]);

        $expectedOrm = new CartLineOrm(
            id: $cartline->id(),
            ownerId: $cartline->ownerId(),
            cartId: $cartline->cartId(),
            productId: $cartline->productId(),
            quantity: $cartline->quantity(),
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($cartline->id());

        $this->entityManager
            ->expects($this->never())
            ->method('remove')
            ->with($expectedOrm);

        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->repository->delete($cartline);
    }
}
