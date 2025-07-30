<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

use App\_Shared\Message\AggregateRoot\Exception\InvalidBoolException;

abstract class BoolValueObject extends ValueObject
{
    public function __construct(
        bool $value,
    ) {
        parent::__construct(value: $value);
    }

    protected function _assert(mixed $value): void {}

    public function value(): bool
    {
        return $this->value;
    }
}
