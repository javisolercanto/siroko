<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

abstract class DateTimeValueObject extends ValueObject
{
    final public function __construct(
        \DateTimeInterface $value,
    ) {
        parent::__construct(value: $value);
    }

    final public static function now(): static
    {
        return new static(new \DateTimeImmutable());
    }

    public function value(): \DateTimeInterface
    {
        return $this->value;
    }
}
