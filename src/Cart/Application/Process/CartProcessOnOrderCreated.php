<?php

declare(strict_types=1);

namespace App\Cart\Application\Process;

use App\_Shared\Domain\DomainEvents\OrderCreatedDomainEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartProcessOnOrderCreated
{
    public function __construct(
        private readonly CartProcessUseCase $useCase,
    ) {}

    public function __invoke(OrderCreatedDomainEvent $event): void
    {
        $command = new CartProcessCommand(cartId: $event->cartId);
        $this->useCase->__invoke($command);
    }
}
