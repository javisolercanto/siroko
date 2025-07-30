<?php

declare(strict_types=1);

namespace App\Tests\Product\Domain\Entity;

use App\Product\Domain\Entity\ProductAvailableStock;
use App\Product\Domain\Exception\ProductInvalidAvailableStockException;
use App\Tests\BaseTestCase;

class ProductAvailableStockTest extends BaseTestCase
{
    public function testNewProductAvailableStock()
    {
        $expected = 10;

        $availablestock = new ProductAvailableStock($expected);

        $this->assertInstanceOf(ProductAvailableStock::class, $availablestock);
        $this->assertEquals($expected, $availablestock->value());
    }

    public function testInvalidAvailableStockIsNegative()
    {
        $this->expectException(ProductInvalidAvailableStockException::class);
        $this->expectExceptionMessage('Available stock cannot be negative');
        new ProductAvailableStock(-1);
    }
}
