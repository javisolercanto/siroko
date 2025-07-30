<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Persistence;

use App\Cart\Infrastructure\Persistence\CartOrm;
use App\Tests\BaseTestCase;

class CartOrmTest extends BaseTestCase
{
    public function testCartOrmNew(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $ownerId = '123e4567-e89b-12d3-a456-426614174001';
        $processed = true;

        $cartOrm = new CartOrm($id, $ownerId, $processed);

        $this->assertSame($id, $cartOrm->id());
        $this->assertSame($ownerId, $cartOrm->ownerId());
        $this->assertSame($processed, $cartOrm->processed());
    }
}
