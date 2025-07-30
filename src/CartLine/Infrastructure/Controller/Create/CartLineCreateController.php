<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Controller\Create;

use OpenApi\Attributes as OA;
use App\_Shared\Infrastructure\ApiController;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\CartLine\Application\Create\CartLineCreateCommand;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;


final class CartLineCreateController extends ApiController
{
    #[Route('/cart-line', name: 'app_cart_line_create', methods: ['POST'])]

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
        content: new Model(type: CartLineCreateRequest::class)
    )]
    #[OA\Tag(name: 'carts_lines')]
    public function create(Request $request, CommandBus $bus): Response
    {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->unauthorized();
        }

        /** @var CartLineCreateRequest|Response $document */
        $document = $this->toDocument(request: $request, document: CartLineCreateRequest::class);
        if ($document instanceof Response) {
            return $document;
        }

        $ownerId = $user->getUserIdentifier();

        $command = new CartLineCreateCommand(
            ownerId: $ownerId,
            cartId: $document->cart_id,
            productId: $document->product_id,
            quantity: $document->quantity,
        );

        $error = $this->safeDispatch(command: $command, bus: $bus);
        if ($error) {
            return $this->error($error->getMessage());
        }

        return $this->accepted();
    }
}
