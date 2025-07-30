<?php

declare(strict_types=1);

namespace App\User\Application;

use App\User\Domain\Entity\User;

class UserResponseConverter
{
    public function __invoke(User $user): UserResponse
    {
        return new UserResponse(
            id: $user->id(),
            name: $user->name(),
            email: $user->email(),
        );
    }
}
