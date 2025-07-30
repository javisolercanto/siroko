<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

use App\_Shared\Message\AggregateRoot\Exception\InvalidUuidException;
use Ramsey\Uuid\Uuid;

class UuidValueObject extends StringValueObject
{
    final public function __construct(
        string $value,
    ) {
        parent::__construct(value: $value);
    }

    /**
     * Generate a new UUID v7
     * @throws InvalidUuidException
     * @return static
     */
    final public static function generate(): static
    {
        return new static(value: Uuid::uuid7()->toString());
    }

    protected function _assert(mixed $value): void
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidUuidException();
        }
    }

    final public static function isValid(string $value): bool
    {
        return Uuid::isValid($value);
    }
}
