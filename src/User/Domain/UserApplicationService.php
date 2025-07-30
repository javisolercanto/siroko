<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\Entity\UserId;

class UserApplicationService implements UserRepositoryInterface
{
    public function __construct(
        private readonly DomainEventBus $eventBus,
        private readonly UserRepositoryInterface $repository,
    ) {}

    public function find(UserId $id): ?User
    {
        return $this->repository->find(id: $id);
    }

    public function findByEmail(UserEmail $email): ?User
    {
        return $this->repository->findByEmail(email: $email);
    }

    public function save(User $user): User
    {
        $this->repository->save(user: $user);
        $user->dispatchEvents($this->eventBus);

        return $user;
    }
}
