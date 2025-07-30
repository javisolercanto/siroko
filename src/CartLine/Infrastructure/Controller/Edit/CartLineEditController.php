<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Controller\Edit;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\CartLine\Application\Edit\CartLineEditCommand;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

final class CartLineEditController extends ApiController
{
    #[Route('/cart-line/{id}', name: 'app_cart_line_edit', methods: ['PUT'])]
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
    #[OA\RequestBody(
        content: new Model(type: CartLineEditRequest::class)
    )]
    #[OA\Tag(name: 'carts_lines')]
    public function edit(string $id, Request $request, CommandBus $bus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        /** @var CartLineEditRequest|Response $document */
        $document = $this->toDocument(request: $request, document: CartLineEditRequest::class);
        if ($document instanceof Response) {
            return $document;
        }

        $command = new CartLineEditCommand(
            cartLineId: $id,
            ownerId: $user->getUserIdentifier(),
            quantity: $document->quantity,
        );

        $error = $this->safeDispatch(command: $command, bus: $bus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        return $this->accepted();
    }
}
