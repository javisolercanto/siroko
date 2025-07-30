<?php

declare(strict_types=1);

namespace App\Tests\Order\Domain\Entity;

use App\Order\Domain\Entity\OrderDate;
use App\Order\Domain\Exception\OrderInvalidDateException;
use App\Tests\BaseTestCase;

class OrderDateTest extends BaseTestCase
{
    public function testNewOrderDate()
    {
        $expected = new \DateTime();

        $date = new OrderDate($expected);

        $this->assertInstanceOf(OrderDate::class, $date);
        $this->assertEquals($expected, $date->value());
    }

    public function testInvalidDate()
    {
        $this->expectException(OrderInvalidDateException::class);
        $this->expectExceptionMessage('Date cannot be in the future');

        $date = new \DateTime();
        $date->add(new \DateInterval('P1D'));

        new OrderDate($date);
    }
}
