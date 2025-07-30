<?php

declare(strict_types=1);

namespace App\Tests\Product\Domain\Entity;

use App\Product\Domain\Entity\ProductPrice;
use App\Product\Domain\Exception\ProductInvalidPriceException;
use App\Tests\BaseTestCase;

class ProductPriceTest extends BaseTestCase
{
    public function testNewProductPrice()
    {
        $expected = 50.0;

        $price = new ProductPrice($expected);

        $this->assertInstanceOf(ProductPrice::class, $price);
        $this->assertEquals($expected, $price->value());
    }

    public function testInvalidPriceIsNegative()
    {
        $this->expectException(ProductInvalidPriceException::class);
        $this->expectExceptionMessage('Price cannot be negative');
        new ProductPrice(-1);
    }
}
