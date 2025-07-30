<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller\Create;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\CartResponse;
use App\Cart\Application\Create\CartCreateCommand;
use App\Cart\Application\FindByOwnerId\CartFindByOwnerIdQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


final class CartCreateController extends ApiController
{
    #[Route('/cart', name: 'app_cart_create', methods: ['POST'])]

    #[OA\Response(
        response: 202,
        description: 'Returns a confirmation message',
    )]
    #[OA\Response(
        response: 401,
        description: 'Authentication required',
    )]
    #[OA\Response(
        response: 403,
        description: 'Access denied',
    )]
    #[OA\Tag(name: 'carts')]
    public function create(CommandBus $bus, QueryBus $queryBus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        $ownerId = $user->getUserIdentifier();
        $query = new CartFindByOwnerIdQuery(ownerId: $ownerId);

        /** @var CartResponse|null $cart */
        $cart = $queryBus->ask($query);
        if ($cart !== null) {
            return $this->toJson($cart);
        }

        $command = new CartCreateCommand(ownerId: $ownerId);

        $error = $this->safeDispatch(command: $command, bus: $bus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        /** @var CartResponse|null $cart */
        $cart = $queryBus->ask($query);
        if ($cart === null) {
            return $this->notFound();
        }

        return $this->toJson($cart);
    }
}
