<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Exception\InvalidUuidException;
use App\Order\Domain\Entity\OrderOwnerId;
use App\Tests\BaseTestCase;

class OrderOwnerIdTest extends BaseTestCase
{
    public function testNewOrderOwnerId()
    {
        $expected = '0198522b-6e53-763f-a6c3-1c81448a5f4b';

        $ownerId = new OrderOwnerId($expected);

        $this->assertInstanceOf(OrderOwnerId::class, $ownerId);
        $this->assertEquals($expected, $ownerId->value());
    }

    public function testInvalidOwnerId()
    {
        $this->expectException(InvalidUuidException::class);
        $this->expectExceptionMessage('Invalid UUID');
        new OrderOwnerId('owner_id');
    }
}
