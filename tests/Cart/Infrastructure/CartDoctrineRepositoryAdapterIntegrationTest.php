<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Infrastructure\CartDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;

class CartDoctrineRepositoryAdapterIntegrationTest extends BaseTestCase
{
    private CartDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CartDoctrineRepositoryAdapter($this->em);
    }

    public function testCartDoctrineRepositoryAdapterIntegrationFind(): void
    {
        $id = '0198521d-7868-7d8f-9737-9789a7e1604c';

        $found = $this->repository->find(new CartId($id));

        $this->assertNotNull($found);
        $this->assertSame($id, $found->id());
        $this->assertInstanceOf(Cart::class, $found);
    }

    public function testCartDoctrineRepositoryAdapterIntegrationFindError(): void
    {
        $id = '0198521d-7868-7d8f-9737-9789a7e1604f';

        $found = $this->repository->find(new CartId($id));

        $this->assertNull($found);
    }

    public function testCartDoctrineRepositoryAdapterIntegrationSaveAndFind(): void
    {
        $cart = Cart::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
        ]);

        $id = $cart->id();

        $this->repository->save($cart);

        $found = $this->repository->find(new CartId($id));

        $this->assertNotNull($found);
        $this->assertSame($cart->id(), $found->id());
        $this->assertSame($cart->ownerId(), $found->ownerId());
        $this->assertInstanceOf(Cart::class, $found);
    }
}
