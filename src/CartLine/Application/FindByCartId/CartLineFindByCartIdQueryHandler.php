<?php

declare(strict_types=1);

namespace App\CartLine\Application\FindByCartId;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\CartLine\Application\CartLineResponse;
use App\CartLine\Application\CartLineResponseConverter;
use App\CartLine\Domain\Entity\CartLine;

#[AsMessageHandler()]
class CartLineFindByCartIdQueryHandler
{
    public function __construct(
        private readonly CartLineFindByCartIdUseCase $useCase,
        private readonly CartLineResponseConverter $responseConverter,
    ) {}

    /**
     * @return CartLineResponse[]
     */
    public function __invoke(CartLineFindByCartIdQuery $query): array
    {
        /** @var CartLine[] $cartLines */
        $cartLines = $this->useCase->__invoke($query);

        return array_map(fn(CartLine $cartLine) => $this->responseConverter->__invoke($cartLine), $cartLines);
    }
}
