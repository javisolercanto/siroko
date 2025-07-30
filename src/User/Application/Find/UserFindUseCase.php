<?php

declare(strict_types=1);

namespace App\User\Application\Find;

use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserId;
use App\User\Domain\UserApplicationService;

class UserFindUseCase
{
    public function __construct(
        private readonly UserApplicationService $applicationService,
    ) {}

    public function __invoke(UserFindQuery $query): ?User
    {
        return $this->applicationService->find(id: new UserId($query->id));
    }
}
