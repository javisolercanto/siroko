<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\DataFixtures;

use App\Cart\Infrastructure\Persistence\CartOrm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class CartFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cart = new CartOrm(
            id: '0198521d-7868-7d8f-9737-9789a7e1604c',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            processed: true,
        );

        $manager->persist($cart);
        $manager->flush();

        $cart2 = new CartOrm(
            id: '0198521d-7868-7d8f-9737-9789a7e1604d',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            processed: false,
        );

        $manager->persist($cart2);
        $manager->flush();
    }
}
