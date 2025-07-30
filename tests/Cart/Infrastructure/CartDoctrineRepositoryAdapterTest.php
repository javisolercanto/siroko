<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Infrastructure\Persistence\CartOrm;
use App\Cart\Infrastructure\CartDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;

class CartDoctrineRepositoryAdapterTest extends BaseTestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private EntityRepository&MockObject $repositoryMock;
    private CartDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(CartOrm::class)
            ->willReturn($this->repositoryMock);

        $this->repository = new CartDoctrineRepositoryAdapter($this->entityManager);
    }


    public function testCartDoctrineRepositoryAdapterFind(): void
    {
        $id = CartId::generate();

        $expected = Cart::fromPrimitives([
            'id' => $id->value(),
            'owner_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'processed' => false,
        ]);

        $expectedOrm = new CartOrm($expected->id(), $expected->ownerId(), $expected->processed());

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn($expectedOrm);

        $cart = $this->repository->find($id);

        $this->assertEquals($expected, $cart);
    }

    public function testCartDoctrineRepositoryAdapterFindNotFound(): void
    {
        $id = CartId::generate();

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn(null);

        $cart = $this->repository->find($id);

        $this->assertNull($cart);
    }

    public function testCartDoctrineRepositoryAdapterSave(): void
    {
        $cart = Cart::create([
            'owner_id' => '019852d0-c479-7eec-819f-0f30942a48a5',
            'processed' => false
        ]);

        $expectedOrm = new CartOrm($cart->id(), $cart->ownerId(), $cart->processed());

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (CartOrm $actual) use ($expectedOrm) {
                return $actual->id() === $expectedOrm->id() &&
                    $actual->ownerId() === $expectedOrm->ownerId();
            }));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->repository->save($cart);

        $this->assertSame($cart, $result);
    }
}
