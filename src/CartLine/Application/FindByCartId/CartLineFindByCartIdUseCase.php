<?php

declare(strict_types=1);

namespace App\CartLine\Application\FindByCartId;

use App\CartLine\Domain\CartLineApplicationService;
use App\CartLine\Domain\Entity\CartLine;
use App\CartLine\Domain\Entity\CartLineCartId;

class CartLineFindByCartIdUseCase
{
    public function __construct(
        private readonly CartLineApplicationService $applicationService,
    ) {}

    /**
     * @return CartLine[]
     */
    public function __invoke(CartLineFindByCartIdQuery $query): array
    {
        return $this->applicationService->findByCartId(new CartLineCartId($query->cartId));
    }
}
