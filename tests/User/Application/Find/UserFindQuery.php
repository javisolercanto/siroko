<?php

declare(strict_types=1);

namespace App\User\Application\Find;

use App\_Shared\Message\Query\Domain\Query;

final class UserFindQuery implements Query
{
    public function __construct(
        public readonly string $id,
    ) {}
}
