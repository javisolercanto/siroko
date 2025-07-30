<?php

declare(strict_types=1);

namespace App\Order\Application\Create;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class OrderCreateCommandHandler
{
    public function __construct(
        private readonly OrderCreateUseCase $useCase,
    ) {}

    public function __invoke(OrderCreateCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
