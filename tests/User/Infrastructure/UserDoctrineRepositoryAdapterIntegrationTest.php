<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure;

use App\Tests\BaseTestCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\Entity\UserId;
use App\User\Infrastructure\UserDoctrineRepositoryAdapter;

class UserDoctrineRepositoryAdapterIntegrationTest extends BaseTestCase
{
    private UserDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new UserDoctrineRepositoryAdapter($this->em);
    }

    public function testUserDoctrineRepositoryAdapterIntegrationFind(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';

        $found = $this->repository->find(new UserId($id));

        $this->assertNotNull($found);
        $this->assertSame($id, $found->id());
        $this->assertInstanceOf(User::class, $found);
    }

    public function testUserDoctrineRepositoryAdapterIntegrationFindError(): void
    {
        $id = '01984fd5-150d-7309-8816-eb1b106262ed';

        $found = $this->repository->find(new UserId($id));

        $this->assertNull($found);
    }

    public function testUserDoctrineRepositoryAdapterIntegrationSaveAndFind(): void
    {
        $user = User::create([
            'name' => 'Siroko',
            'email' => '005@siroko.com',
        ]);

        $id = $user->id();

        $this->repository->save($user);

        $found = $this->repository->find(new UserId($id));

        $this->assertNotNull($found);
        $this->assertSame($user->id(), $found->id());
        $this->assertSame($user->name(), $found->name());
        $this->assertSame($user->email(), $found->email());
        $this->assertInstanceOf(User::class, $found);
    }

    public function testUserDoctrineRepositoryAdapterIntegrationFindByEmail(): void
    {
        $email = 'user@siroko.com';

        $found = $this->repository->findByEmail(new UserEmail($email));

        $this->assertNotNull($found);
        $this->assertSame($email, $found->email());
        $this->assertInstanceOf(User::class, $found);
    }

    public function testUserDoctrineRepositoryAdapterIntegrationFindByEmailNotFound(): void
    {
        $email = 'user2@siroko.com';

        $found = $this->repository->findByEmail(new UserEmail($email));

        $this->assertNull($found);
    }
}
