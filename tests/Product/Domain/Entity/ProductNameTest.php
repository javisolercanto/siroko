<?php

declare(strict_types=1);

namespace App\Tests\Product\Domain\Entity;

use App\Product\Domain\Entity\ProductName;
use App\Product\Domain\Exception\ProductInvalidNameException;
use App\Tests\BaseTestCase;

class ProductNameTest extends BaseTestCase
{
    public function testNewProductName()
    {
        $expected = 'Maillot';

        $name = new ProductName($expected);

        $this->assertInstanceOf(ProductName::class, $name);
        $this->assertEquals($expected, $name->value());
    }

    public function testInvalidNameShort()
    {
        $this->expectException(ProductInvalidNameException::class);
        $this->expectExceptionMessage('Name too short');
        new ProductName('Ma');
    }

    public function testInvalidNameLong()
    {
        $this->expectException(ProductInvalidNameException::class);
        $this->expectExceptionMessage('Name too long');
        new ProductName('Mailloooooooooooooooooooooooooot');
    }

    public function testInvalidNameEmpty()
    {
        $this->expectException(ProductInvalidNameException::class);
        $this->expectExceptionMessage('Name too short');
        new ProductName('');
    }
}
