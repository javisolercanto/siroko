<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Controller\Find;

use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\Find\CartFindQuery;
use App\Cart\Application\CartResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartFindController extends ApiController
{
    #[Route('/cart/{id}', name: 'app_cart_find', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the cart information',
    )]
    #[OA\Parameter(
        name: 'id',
        in: 'path',
        description: 'The id of the cart',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Tag(name: 'carts')]
    public function find(QueryBus $bus, string $id): Response
    {
        $query = new CartFindQuery(id: $id);

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
