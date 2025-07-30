<?php

declare(strict_types=1);

namespace App\User\Application\FindByEmail;

use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\UserApplicationService;

class UserFindByEmailUseCase
{
    public function __construct(
        private readonly UserApplicationService $applicationService,
    ) {}

    public function __invoke(UserFindByEmailQuery $query): ?User
    {
        return $this->applicationService->findByEmail(email: new UserEmail($query->email));
    }
}
