<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

class UserInvalidNameException extends UserException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Invalid name: ' . $message);
    }
}
