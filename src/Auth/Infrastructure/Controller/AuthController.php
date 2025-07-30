<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Auth\Infrastructure\AuthUser;
use App\User\Application\FindByEmail\UserFindByEmailQuery;
use App\User\Application\UserResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class AuthController extends ApiController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a token',
    )]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', default: 'user@siroko.com'),
            ]
        )
    )]
    #[OA\Tag(name: 'auth')]
    public function auth(Request $request, JWTTokenManagerInterface $jwtManager, QueryBus $bus): Response
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['email'])) {
            return $this->badRequest();
        }

        /** @var UserResponse|null $user */
        [$user, $error] = $this->safeAsk(query: new UserFindByEmailQuery(email: $data['email']), bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        if ($user === null) {
            return $this->unauthorized();
        }

        $authUser = new AuthUser(id: $user->id);
        $token = $jwtManager->create($authUser);

        return $this->toJson([
            'token' => $token,
        ]);
    }
}
