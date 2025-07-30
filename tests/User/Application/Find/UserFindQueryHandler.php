<?php

declare(strict_types=1);

namespace App\User\Application\Find;

use App\User\Application\UserResponse;
use App\User\Application\UserResponseConverter;
use App\User\Domain\Entity\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class UserFindQueryHandler
{
    public function __construct(
        private readonly UserFindUseCase $useCase,
        private readonly UserResponseConverter $converter,
    ) {}

    public function __invoke(UserFindQuery $query): ?UserResponse
    {
        /** @var User|null $user */
        $user = $this->useCase->__invoke($query);
        if ($user === null) {
            return null;
        }

        return $this->converter->__invoke(user: $user);
    }
}
