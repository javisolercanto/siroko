<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure\Persistence;

use App\Product\Infrastructure\Persistence\ProductOrm;
use App\Tests\BaseTestCase;

class ProductOrmTest extends BaseTestCase
{
    public function testProductOrmNew(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $name = 'Maillot';
        $price = 50.0;
        $stock = 10;
        $availableStock = 10;

        $productOrm = new ProductOrm($id, $name, $price, $stock, $availableStock);

        $this->assertSame($id, $productOrm->id());
        $this->assertSame($name, $productOrm->name());
        $this->assertSame($price, $productOrm->price());
        $this->assertSame($stock, $productOrm->stock());
        $this->assertSame($availableStock, $productOrm->availableStock());
    }
}
