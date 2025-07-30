<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\_Shared\Domain\DomainEvents\UserCreatedDomainEvent;
use App\_Shared\Message\AggregateRoot\Entity\AggregateRoot;

class User extends AggregateRoot
{
    final public function __construct(
        UserId $id,
        private readonly UserName $name,
        private readonly UserEmail $email,
    ) {
        parent::__construct(id: $id);
    }

    final public static function create(array $primitives): static
    {
        $user = new static(
            id: UserId::generate(),
            name: new UserName($primitives['name']),
            email: new UserEmail($primitives['email']),
        );

        $event = new UserCreatedDomainEvent(userId: $user->id());
        $user->record($event);

        return $user;
    }

    final public static function fromPrimitives(array $primitives): static
    {
        return new static(
            id: new UserId($primitives['id']),
            name: new UserName($primitives['name']),
            email: new UserEmail($primitives['email']),
        );
    }

    final public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'email' => $this->email->value(),
        ];
    }

    final public function name(): string
    {
        return $this->name->value();
    }

    final public function email(): string
    {
        return $this->email->value();
    }
}
