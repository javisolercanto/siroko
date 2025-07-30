<?php

declare(strict_types=1);

namespace App\Order\Application\Find;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderOwnerId;
use App\Order\Domain\OrderApplicationService;

class OrderFindByOwnerIdUseCase
{
    public function __construct(
        private readonly OrderApplicationService $applicationService,
    ) {}

    /**
     * @return Order[]
     */
    public function __invoke(OrderFindByOwnerIdQuery $query): array
    {
        return $this->applicationService->findByOwnerId(ownerId: new OrderOwnerId($query->ownerId));
    }
}
