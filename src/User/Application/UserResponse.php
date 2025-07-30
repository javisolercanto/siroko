<?php

declare(strict_types=1);

namespace App\User\Application;

use App\_Shared\Application\Response;

class UserResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
    ) {}
}
