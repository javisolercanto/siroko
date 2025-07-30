<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Controller\FindByCartId;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\CartResponse;
use App\Cart\Application\Find\CartFindQuery;
use App\CartLine\Application\CartLineResponse;
use App\CartLine\Application\FindByCartId\CartLineFindByCartIdQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


final class CartLineFindByCartIdController extends ApiController
{
    #[Route('/cart-line/find-by-cart-id/{id}', name: 'app_cart_line_find_by_cart_id', methods: ['GET'])]

    #[OA\Response(
        response: 200,
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
    public function findByCartId(string $id, QueryBus $bus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        $ownerId = $user->getUserIdentifier();

        $query = new CartFindQuery(id: $id);

        /** @var CartResponse|null $cart */
        [$cart, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        if ($cart === null) {
            return $this->notFound();
        }

        if ($cart->owner_id !== $ownerId) {
            return $this->forbidden();
        }

        $query = new CartLineFindByCartIdQuery(cartId: $cart->id);

        /** @var CartLineResponse[] $response */
        [$response, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        return $this->toJson($response);
    }
}
