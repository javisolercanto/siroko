<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Find;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\User\Application\Find\UserFindQuery;
use App\User\Application\UserResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserFindController extends ApiController
{
    #[Route('/user/{id}', name: 'app_user_find', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the user information',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The id of the user',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'users')]
    public function find(QueryBus $bus, string $id): Response
    {
        $query = new UserFindQuery(id: $id);

        /** @var UserResponse|null $user */
        [$user, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        if ($user === null) {
            return $this->notFound();
        }

        return $this->toJson($user);
    }
}
