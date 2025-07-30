<?php

declare(strict_types=1);

namespace App\_Shared\Message\AggregateRoot\Exception;

class InvalidUuidException extends BaseException
{

    public function __construct(string $message = 'Invalid UUID')
    {
        parent::__construct(message: $message);
    }
}
