<?php

declare(strict_types=1);

namespace App\User\Domain\Exception;

use App\_Shared\Message\AggregateRoot\Exception\BaseException;

abstract class UserException extends BaseException
{
    public function __construct(string $message)
    {
        parent::__construct(message: $message);
    }
}
