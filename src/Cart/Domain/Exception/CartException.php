<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\_Shared\Message\AggregateRoot\Exception\BaseException;

abstract class CartException extends BaseException
{
    public function __construct(string $message)
    {
        parent::__construct(message: $message);
    }
}
