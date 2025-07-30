<?php

declare(strict_types=1);

namespace App\User\Application\FindByEmail;

use App\User\Application\UserResponse;
use App\User\Application\UserResponseConverter;
use App\User\Domain\Entity\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class UserFindByEmailQueryHandler
{
    public function __construct(
        private readonly UserFindByEmailUseCase $useCase,
        private readonly UserResponseConverter $converter,
    ) {}

    public function __invoke(UserFindByEmailQuery $query): ?UserResponse
    {
        /** @var User|null $user */
        $user = $this->useCase->__invoke($query);
        if ($user === null) {
            return null;
        }

        return $this->converter->__invoke(user: $user);
    }
}
