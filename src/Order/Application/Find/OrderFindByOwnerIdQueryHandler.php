<?php

declare(strict_types=1);

namespace App\Order\Application\Find;

use App\Order\Application\OrderResponse;
use App\Order\Application\OrderResponseConverter;
use App\Order\Domain\Entity\Order;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class OrderFindByOwnerIdQueryHandler
{
    public function __construct(
        private readonly OrderFindByOwnerIdUseCase $useCase,
        private readonly OrderResponseConverter $converter,
    ) {}

    /**
     * @return OrderResponse[]
     */
    public function __invoke(OrderFindByOwnerIdQuery $query): array
    {
        /** @var Order[] $orders */
        $orders = $this->useCase->__invoke($query);

        return array_map(fn(Order $order) => $this->converter->__invoke($order), $orders);
    }
}
