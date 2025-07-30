<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

abstract class ValueObject
{
    public function __construct(
        protected readonly mixed $value,
    ) {
        $this->_assert($value);
    }

    abstract public function value(): mixed;

    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * Assert if the value has the expected structure
     * 
     * @param mixed $value The value to test
     * @throws \Exception
     */
    abstract protected function _assert(mixed $value): void;
}
