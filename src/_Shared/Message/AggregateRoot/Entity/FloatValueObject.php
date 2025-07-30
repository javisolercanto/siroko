<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

abstract class FloatValueObject extends ValueObject
{
    public function __construct(
        float $value,
    ) {
        parent::__construct(value: $value);
    }

    public function value(): float
    {
        return $this->value;
    }
}
