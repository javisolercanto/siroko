<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

abstract class StringValueObject extends ValueObject
{
    public function __construct(
        string $value,
    ) {
        parent::__construct(value: $value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
