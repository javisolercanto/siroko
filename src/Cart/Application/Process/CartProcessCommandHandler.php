<?php

declare(strict_types=1);

namespace App\Cart\Application\Process;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartProcessCommandHandler
{
    public function __construct(
        private readonly CartProcessUseCase $useCase,
    ) {}

    public function __invoke(CartProcessCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
