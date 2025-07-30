<?php

declare(strict_types=1);

namespace App\Product\Application\EditStock;

use App\_Shared\Domain\DomainEvents\OrderCreatedDomainEvent;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\CartLine\Application\CartLineResponse;
use App\CartLine\Application\FindByCartId\CartLineFindByCartIdQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class ProductEditStockOnOrderCreated
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CommandBus $commandBus,
    ) {}

    public function __invoke(OrderCreatedDomainEvent $event): void
    {
        /** @var CartLineResponse[] $cartLines */
        $cartLines = $this->queryBus->ask(new CartLineFindByCartIdQuery(cartId: $event->cartId));

        foreach ($cartLines as $cartLine) {
            $command = new ProductEditStockCommand(
                productId: $cartLine->product_id,
                quantity: $cartLine->quantity,
            );

            $this->commandBus->dispatch($command);
        }
    }
}
