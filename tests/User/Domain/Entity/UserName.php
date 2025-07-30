<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\StringValueObject;
use App\User\Domain\Exception\UserInvalidNameException;

class UserName extends StringValueObject
{

    protected function _assert(mixed $value): void
    {
        if (strlen($value) < 3) {
            throw new UserInvalidNameException('Name too short');
        }

        if (strlen($value) > 30) {
            throw new UserInvalidNameException('Name too long');
        }

        if (!preg_match('/^[a-zA-Z ]+$/', $value)) {
            throw new UserInvalidNameException('Invalid characters');
        }
    }
}
