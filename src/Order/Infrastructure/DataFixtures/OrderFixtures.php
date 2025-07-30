<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\DataFixtures;

use App\Order\Infrastructure\Persistence\OrderOrm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class OrderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $order = new OrderOrm(
            id: '01985828-6df1-7a5b-aa5b-9af694856f30',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            cartId: '0198521d-7868-7d8f-9737-9789a7e1604d',
            date: new \DateTimeImmutable(),
        );

        $manager->persist($order);
        $manager->flush();
    }
}
