<?php

declare(strict_types=1);

namespace App\Product\Application\EditStock;

use App\_Shared\Domain\DomainEvents\CartLineDeletedDomainEvent;
use App\_Shared\Message\Command\Domain\CommandBus;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductEditStockOnCartLineDeleted
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {}

    public function __invoke(CartLineDeletedDomainEvent $event): void
    {
        $command = new ProductEditStockCommand(
            productId: $event->productId,
            quantity: $event->quantity * -1,
        );

        $this->commandBus->dispatch($command);
    }
}
