<?php

declare(strict_types=1);

namespace App\Product\Application\EditAvailableStock;

use App\_Shared\Domain\DomainEvents\CartLineCreatedDomainEvent;
use App\_Shared\Message\Command\Domain\CommandBus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductEditAvailableStockOnCartLineCreated
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {}

    public function __invoke(CartLineCreatedDomainEvent $event): void
    {
        $command = new ProductEditAvailableStockCommand(
            productId: $event->product_id,
            quantity: $event->quantity,
        );

        $this->commandBus->dispatch($command);
    }
}
