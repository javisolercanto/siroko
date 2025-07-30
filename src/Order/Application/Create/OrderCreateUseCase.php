<?php

declare(strict_types=1);

namespace App\Order\Application\Create;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\OrderApplicationService;

class OrderCreateUseCase
{
    public function __construct(
        private readonly OrderApplicationService $applicationService,
    ) {}

    public function __invoke(OrderCreateCommand $command): void
    {
        $order = Order::create($command->toPrimitives());

        $this->applicationService->save($order);
    }
}
