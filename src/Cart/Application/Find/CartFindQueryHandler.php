<?php

declare(strict_types=1);

namespace App\Cart\Application\Find;

use App\Cart\Application\CartResponse;
use App\Cart\Application\CartResponseConverter;
use App\Cart\Domain\Entity\Cart;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class CartFindQueryHandler
{
    public function __construct(
        private readonly CartFindUseCase $useCase,
        private readonly CartResponseConverter $converter,
    ) {}

    public function __invoke(CartFindQuery $query): ?CartResponse
    {
        /** @var Cart|null $cart */
        $cart = $this->useCase->__invoke($query);
        if ($cart === null) {
            return null;
        }

        return $this->converter->__invoke(cart: $cart);
    }
}
