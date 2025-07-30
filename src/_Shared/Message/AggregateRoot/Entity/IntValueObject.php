<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

abstract class IntValueObject extends ValueObject
{
    public function __construct(
        int $value,
    ) {
        parent::__construct(value: $value);
    }

    public function value(): int
    {
        return $this->value;
    }
}
