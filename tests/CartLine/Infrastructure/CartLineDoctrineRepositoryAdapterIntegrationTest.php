<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineId;
use App\CartLine\Infrastructure\CartLineDoctrineRepositoryAdapter;
use App\Tests\BaseTestCase;

class CartLineDoctrineRepositoryAdapterIntegrationTest extends BaseTestCase
{
    private CartLineDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CartLineDoctrineRepositoryAdapter($this->em);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationFind(): void
    {
        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $found = $this->repository->find(new CartLineId($id));

        $this->assertNotNull($found);
        $this->assertSame($id, $found->id());
        $this->assertInstanceOf(CartLine::class, $found);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationFindError(): void
    {
        $id = '0198521d-7868-7d8f-9737-9789a7e1604f';

        $found = $this->repository->find(new CartLineId($id));

        $this->assertNull($found);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationSaveAndFind(): void
    {
        $cartline = CartLine::create([
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '123e4567-e89b-12d3-a456-426614174001',
            'product_id' => '123e4567-e89b-12d3-a456-426614174002',
            'quantity' => 1,
        ]);

        $id = $cartline->id();

        $this->repository->save($cartline);

        $found = $this->repository->find(new CartLineId($id));

        $this->assertNotNull($found);
        $this->assertSame($cartline->id(), $found->id());
        $this->assertSame($cartline->ownerId(), $found->ownerId());
        $this->assertSame($cartline->cartId(), $found->cartId());
        $this->assertSame($cartline->productId(), $found->productId());
        $this->assertSame($cartline->quantity(), $found->quantity());
        $this->assertInstanceOf(CartLine::class, $found);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationDelete(): void
    {
        $cartline = CartLine::create([
            'owner_id' => UuidValueObject::generate()->value(),
            'cart_id' => UuidValueObject::generate()->value(),
            'product_id' => UuidValueObject::generate()->value(),
            'quantity' => 1,
        ]);

        $this->repository->save($cartline);
        $this->repository->delete($cartline);

        $found = $this->repository->find(new CartLineId($cartline->id()));

        $this->assertNull($found);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationEdit(): void
    {
        $cartline = CartLine::fromPrimitives([
            'id' => '019855ee-74c0-73a8-83b0-0b91ee63342c',
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '0198521d-7868-7d8f-9737-9789a7e1604c',
            'product_id' => '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            'quantity' => 2,
        ]);

        $updated = $this->repository->update($cartline);

        $this->assertNotNull($updated);
        $this->assertSame($cartline->id(), $updated->id());
        $this->assertSame($cartline->ownerId(), $updated->ownerId());
        $this->assertSame($cartline->cartId(), $updated->cartId());
        $this->assertSame($cartline->productId(), $updated->productId());
        $this->assertSame($cartline->quantity(), $updated->quantity());
        $this->assertInstanceOf(CartLine::class, $updated);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationEditError(): void
    {
        $cartline = CartLine::fromPrimitives([
            'id' => '019855ee-74c0-73a8-83b0-0b91ee63342c',
            'owner_id' => '123e4567-e89b-12d3-a456-426614174000',
            'cart_id' => '0198521d-7868-7d8f-9737-9789a7e1604c',
            'product_id' => '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            'quantity' => 1,
        ]);

        $updated = $this->repository->update($cartline);

        $this->assertNotNull($updated);
        $this->assertSame($cartline->id(), $updated->id());
        $this->assertSame($cartline->ownerId(), $updated->ownerId());
        $this->assertSame($cartline->cartId(), $updated->cartId());
        $this->assertSame($cartline->productId(), $updated->productId());
        $this->assertNotEquals(2, $updated->quantity());
        $this->assertInstanceOf(CartLine::class, $updated);
    }

    public function testCartLineDoctrineRepositoryAdapterIntegrationEditNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart line not found');

        $cartline = CartLine::fromPrimitives([
            'id' => UuidValueObject::generate()->value(),
            'owner_id' => UuidValueObject::generate()->value(),
            'cart_id' => UuidValueObject::generate()->value(),
            'product_id' => UuidValueObject::generate()->value(),
            'quantity' => 1,
        ]);

        $this->repository->update($cartline);
    }
}
