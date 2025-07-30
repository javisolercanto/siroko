<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class UserCreateCommandHandler
{

    public function __construct(
        private readonly UserCreateUseCase $useCase,
    ) {}

    public function __invoke(UserCreateCommand $command): void
    {
        $this->useCase->__invoke($command);
    }
}
