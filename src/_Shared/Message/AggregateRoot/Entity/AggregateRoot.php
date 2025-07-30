<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Entity;

use App\_Shared\Message\Event\Domain\DomainEvent;
use App\_Shared\Message\Event\Domain\DomainEventBus;

abstract class AggregateRoot
{
    /** @var DomainEvent[] $domainEvents */
    protected array $domainEvents = [];

    public function __construct(
        protected readonly UuidValueObject $id,
    ) {}

    public function id(): string
    {
        return $this->id->value();
    }

    /**
     * Create a new aggregate root from a primitive array
     * 
     * @param array<string, mixed> $primitives Key value array
     * @return static
     */
    abstract public static function create(array $primitives): static;

    /**
     * Build an aggregate root from a primitive array
     * 
     * @param array<string, mixed> $primitives Key value array
     * @return static
     */
    abstract public static function fromPrimitives(array $primitives): static;

    /**
     * Build a primitive array from the aggregate root
     * 
     * @return array<string, mixed>
     */
    abstract public function toPrimitives(): array;

    final public function record(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    final public function dispatchEvents(DomainEventBus $bus): void
    {
        foreach ($this->pullEvents() as $event) {
            $bus->publish($event);
        }
    }

    /**
     * Get all domain events and clear them from the aggregate
     * 
     * @return DomainEvent[]
     */
    final public function pullEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->clearEvents();

        return $domainEvents;
    }

    final public function clearEvents(): void
    {
        $this->domainEvents = [];
    }
}
