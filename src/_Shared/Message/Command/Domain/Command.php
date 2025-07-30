<?php

declare(strict_types=1);

namespace App\_Shared\Message\Command\Domain;

abstract class Command
{
    public function __construct(
        private readonly string $aggregateId,
    ) {}

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * Build a primitive array from the command
     * 
     * @return array<string, mixed>
     */
    abstract public function toPrimitives(): array;

    /**
     * Build a command from a primitive array
     * 
     * @param string $aggregateId
     * @param array<string, mixed> $primitives
     */
    abstract public static function fromPrimitives(string $aggregateId, array $primitives): static;
}
