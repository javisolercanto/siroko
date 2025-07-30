<?php

declare(strict_types=1);

namespace App\CartLine\Application\Create;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartLineCreateCommandHandler
{
    public function __construct(
        private readonly CartLineCreateUseCase $useCase,
    ) {}

    public function __invoke(CartLineCreateCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
