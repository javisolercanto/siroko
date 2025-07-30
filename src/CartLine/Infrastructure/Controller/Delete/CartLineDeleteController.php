<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Controller\Delete;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\CartLine\Application\CartLineResponse;
use App\CartLine\Application\Delete\CartLineDeleteCommand;
use App\CartLine\Application\Delete\CartLineDeleteQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


final class CartLineDeleteController extends ApiController
{
    #[Route('/cart-line/{id}', name: 'app_cart_line_delete', methods: ['DELETE'])]

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
    #[OA\Tag(name: 'carts_lines')]
    public function delete(string $id, CommandBus $bus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        $ownerId = $user->getUserIdentifier();

        $command = new CartLineDeleteCommand(id: $id, ownerId: $ownerId);

        $error = $this->safeDispatch(command: $command, bus: $bus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        return $this->accepted();
    }
}
