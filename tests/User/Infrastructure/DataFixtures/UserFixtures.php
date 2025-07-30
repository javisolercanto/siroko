<?php

declare(strict_types=1);

namespace App\User\Infrastructure\DataFixtures;

use App\User\Infrastructure\Persistence\UserOrm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new UserOrm(
            id: '123e4567-e89b-12d3-a456-426614174000',
            name: 'Siroko',
            email: 'user@siroko.com'
        );

        $manager->persist($user);
        $manager->flush();

        $user2 = new UserOrm(
            id: '01985a76-b787-7c4c-ab8b-542aa31264eb',
            name: 'Javier',
            email: 'javier@siroko.com'
        );

        $manager->persist($user2);
        $manager->flush();
    }
}
