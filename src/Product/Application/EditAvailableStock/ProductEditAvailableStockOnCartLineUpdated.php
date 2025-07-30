<?php

declare(strict_types=1);

namespace App\Product\Application\EditAvailableStock;

use App\_Shared\Domain\DomainEvents\CartLineUpdatedDomainEvent;
use App\_Shared\Message\Command\Domain\CommandBus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductEditAvailableStockOnCartLineUpdated
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {}

    public function __invoke(CartLineUpdatedDomainEvent $event): void
    {
        $command = new ProductEditAvailableStockCommand(
            productId: $event->productId,
            quantity: $event->newQuantity - $event->oldQuantity
        );

        $this->commandBus->dispatch($command);
    }
}
