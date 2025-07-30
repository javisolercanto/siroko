<?php

declare(strict_types=1);

namespace App\Cart\Application\Find;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartId;
use App\Cart\Domain\CartApplicationService;

class CartFindUseCase
{
    public function __construct(
        private readonly CartApplicationService $applicationService,
    ) {}

    public function __invoke(CartFindQuery $query): ?Cart
    {
        return $this->applicationService->find(id: new CartId($query->id));
    }
}
