<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Controller\Create;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\CartResponse;
use App\Cart\Application\Find\CartFindQuery;
use App\Order\Application\Create\OrderCreateCommand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


final class OrderCreateController extends ApiController
{
    #[Route('/order/create-from-cart/{id}', name: 'app_order_create', methods: ['POST'])]
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
    #[OA\Tag(name: 'orders')]
    public function create(CommandBus $commandBus, QueryBus $queryBus, string $id): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        $ownerId = $user->getUserIdentifier();

        $query = new CartFindQuery(id: $id);
        /** @var CartResponse|null $cart */
        [$cart, $error] = $this->safeAsk(query: $query, bus: $queryBus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        if ($cart === null) {
            return $this->notFound();
        }

        $command = new OrderCreateCommand(
            ownerId: $ownerId,
            cartId: $cart->id,
        );

        $error = $this->safeDispatch(command: $command, bus: $commandBus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        return $this->accepted();
    }
}
