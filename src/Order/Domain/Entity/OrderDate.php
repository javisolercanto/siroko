<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\DateTimeValueObject;
use App\Order\Domain\Exception\OrderInvalidDateException;

class OrderDate extends DateTimeValueObject
{
    protected function _assert(mixed $value): void
    {
        if ($value > new \DateTime()) {
            throw new OrderInvalidDateException('Date cannot be in the future');
        }
    }
}
