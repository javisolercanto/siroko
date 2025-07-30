<?php

declare(strict_types=1);

namespace App\Cart\Application\Create;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartCreateCommandHandler
{
    public function __construct(
        private readonly CartCreateUseCase $useCase,
    ) {}

    public function __invoke(CartCreateCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
