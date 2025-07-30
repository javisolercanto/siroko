<?php

declare(strict_types=1);

namespace App\Tests\Product\Domain\Entity;

use App\Product\Domain\Entity\ProductStock;
use App\Product\Domain\Exception\ProductInvalidStockException;
use App\Tests\BaseTestCase;

class ProductStockTest extends BaseTestCase
{
    public function testNewProductStock()
    {
        $expected = 10;

        $stock = new ProductStock($expected);

        $this->assertInstanceOf(ProductStock::class, $stock);
        $this->assertEquals($expected, $stock->value());
    }

    public function testInvalidStockIsNegative()
    {
        $this->expectException(ProductInvalidStockException::class);
        $this->expectExceptionMessage('Stock cannot be negative');
        new ProductStock(-1);
    }
}
