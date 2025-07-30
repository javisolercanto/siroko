<?php

declare(strict_types=1);

namespace App\CartLine\Application\Delete;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartLineDeleteCommandHandler
{
    public function __construct(
        private readonly CartLineDeleteUseCase $useCase,
    ) {}

    public function __invoke(CartLineDeleteCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
