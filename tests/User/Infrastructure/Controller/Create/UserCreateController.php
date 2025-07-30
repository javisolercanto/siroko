<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Create;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\User\Application\Create\UserCreateCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserCreateController extends ApiController
{
    #[Route('/user', name: 'app_user_create', methods: ['POST'])]
    #[OA\Response(
        response: 202,
        description: 'Returns a confirmation message',
    )]
    #[OA\RequestBody(
        content: new Model(type: UserCreateRequest::class)
    )]
    #[OA\Tag(name: 'users')]
    public function create(CommandBus $bus, Request $request): Response
    {
        /** @var UserCreateRequest|Response $document */
        $document = $this->toDocument(request: $request, document: UserCreateRequest::class);
        if ($document instanceof Response) {
            return $document;
        }

        $command = new UserCreateCommand(
            name: $document->name,
            email: $document->email,
        );

        $error = $this->safeDispatch(command: $command, bus: $bus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        return $this->accepted();
    }
}
