<?php

declare(strict_types=1);

namespace App\Product\Domain\Enum;

enum ProductStockOperationEnum: string
{
    case INCREASE = 'increase';
    case DECREASE = 'decrease';
}
