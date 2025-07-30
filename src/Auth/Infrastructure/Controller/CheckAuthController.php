<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\User\Application\Find\UserFindQuery;
use App\User\Application\UserResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class CheckAuthController extends ApiController
{
    #[Route('/check', name: 'check', methods: ['GET'])]
    #[OA\Tag(name: 'auth')]
    public function check(QueryBus $bus): Response
    {
        $ownerId = $this->getUser()?->getUserIdentifier();
        if ($ownerId === null) {
            return $this->unauthorized();
        }

        /** @var UserResponse|null $user */
        [$user, $error] = $this->safeAsk(query: new UserFindQuery(id: $ownerId), bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        if ($user === null) {
            return $this->unauthorized();
        }

        return $this->toJson($user);
    }
}
