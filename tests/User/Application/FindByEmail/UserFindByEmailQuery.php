<?php

declare(strict_types=1);

namespace App\User\Application\FindByEmail;

use App\_Shared\Message\Query\Domain\Query;

final class UserFindByEmailQuery implements Query
{
    public function __construct(
        public readonly string $email,
    ) {}
}
