<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use App\User\Domain\Entity\User;
use App\User\Domain\UserApplicationService;

class UserCreateUseCase
{

    public function __construct(
        private readonly UserApplicationService $applicationService,
    ) {}

    public function __invoke(UserCreateCommand $command): void
    {
        $user = User::create($command->toPrimitives());

        $this->applicationService->save($user);
    }
}
