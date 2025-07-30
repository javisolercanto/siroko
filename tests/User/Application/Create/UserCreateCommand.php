<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use App\_Shared\Message\Command\Domain\Command;

final class UserCreateCommand extends Command
{

    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {
        parent::__construct(aggregateId: '');
    }

    public function toPrimitives(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    public static function fromPrimitives(string $aggregateId, array $primitives): static
    {
        return new static(
            name: $primitives['name'],
            email: $primitives['email'],
        );
    }
}
