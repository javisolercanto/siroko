<?php

declare(strict_types=1);

namespace App\Cart\Application\FindByOwnerId;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\CartApplicationService;
use App\Cart\Domain\Entity\CartOwnerId;

class CartFindByOwnerIdUseCase
{
    public function __construct(
        private readonly CartApplicationService $applicationService,
    ) {}

    public function __invoke(CartFindByOwnerIdQuery $query): ?Cart
    {
        return $this->applicationService->findByOwnerId(ownerId: new CartOwnerId($query->ownerId));
    }
}
