<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\DataFixtures;

use App\Product\Infrastructure\Persistence\ProductOrm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $maillot = new ProductOrm(
            id: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            name: 'Maillot',
            price: 50.0,
            stock: 10,
            availableStock: 10
        );

        $manager->persist($maillot);
        $manager->flush();

        $helmet = new ProductOrm(
            id: '019850bc-0cc9-74f3-886f-ff13031f2fc9',
            name: 'Helmet',
            price: 100.0,
            stock: 2,
            availableStock: 2
        );

        $manager->persist($helmet);
        $manager->flush();
    }
}
