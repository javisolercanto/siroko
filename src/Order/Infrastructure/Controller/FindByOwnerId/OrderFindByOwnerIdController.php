<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Controller\FindByOwnerId;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Order\Application\Find\OrderFindByOwnerIdQuery;
use App\Order\Application\OrderResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderFindByOwnerIdController extends ApiController
{
    #[Route('/order', name: 'app_order_find', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the orders of the user',
    )]
    #[OA\Tag(name: 'orders')]
    public function findByOwnerId(QueryBus $bus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        $ownerId = $user->getUserIdentifier();

        $query = new OrderFindByOwnerIdQuery(ownerId: $ownerId);

        /** @var OrderResponse[] $orders */
        [$orders, $error] = $this->safeAsk(query: $query, bus: $bus);
        if ($error !== null) {
            return $this->error($error->getMessage());
        }

        return $this->toJson($orders);
    }
}
