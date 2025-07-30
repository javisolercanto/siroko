<?php

declare(strict_types=1);

namespace App\CartLine\Application\Edit;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartLineEditCommandHandler
{
    public function __construct(
        private readonly CartLineEditUseCase $useCase,
    ) {}

    public function __invoke(CartLineEditCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
