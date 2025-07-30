<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller\FindByOwnerId;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\CartResponse;
use App\Cart\Application\FindByOwnerId\CartFindByOwnerIdQuery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class CartFindByOwnerIdController extends ApiController
{
    #[Route('/cart', name: 'app_cart_find_by_owner_id', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the cart information',
    )]
    #[OA\Tag(name: 'carts')]
    public function findByOwnerId(QueryBus $bus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        $ownerId = $user->getUserIdentifier();

        $query = new CartFindByOwnerIdQuery(ownerId: $ownerId);

        /** @var CartResponse|null $cart */
        [$cart, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        if ($cart === null) {
            return $this->notFound();
        }

        return $this->toJson($cart);
    }
}
