<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\_Shared\Message\AggregateRoot\Entity\StringValueObject;
use App\User\Domain\Exception\UserInvalidEmailException;

class UserEmail extends StringValueObject
{

    protected function _assert(mixed $value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new UserInvalidEmailException();
        }
    }
}
