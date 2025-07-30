<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\DataFixtures;

use App\CartLine\Infrastructure\Persistence\CartLineOrm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class CartLineFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cartLine = new CartLineOrm(
            id: '019855ee-74c0-73a8-83b0-0b91ee63342c',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            cartId: '0198521d-7868-7d8f-9737-9789a7e1604c',
            productId: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            quantity: 1,
        );

        $manager->persist($cartLine);
        $manager->flush();

        $cartLine2 = new CartLineOrm(
            id: '019855ee-74c0-73a8-83b0-0b91ee63342d',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            cartId: '0198521d-7868-7d8f-9737-9789a7e1604d',
            productId: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            quantity: 1,
        );

        $manager->persist($cartLine2);
        $manager->flush();
    }
}
