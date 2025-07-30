<?php

declare(strict_types=1);

namespace App\Tests\Cart\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Exception\InvalidUuidException;
use App\Cart\Domain\Entity\CartOwnerId;
use App\Tests\BaseTestCase;

class CartOwnerIdTest extends BaseTestCase
{
    public function testNewCartOwnerId()
    {
        $expected = '0198522b-6e53-763f-a6c3-1c81448a5f4b';

        $ownerId = new CartOwnerId($expected);

        $this->assertInstanceOf(CartOwnerId::class, $ownerId);
        $this->assertEquals($expected, $ownerId->value());
    }

    public function testInvalidOwnerId()
    {
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('Invalid UUID');
        new CartOwnerId('owner_id');
    }
}
