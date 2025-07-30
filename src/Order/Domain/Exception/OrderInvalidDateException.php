<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

class OrderInvalidDateException extends OrderException
{
    public function __construct(
        string $message = '',
    ) {
        parent::__construct('Order invalid date: ' . $message);
    }
}
