<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure;

use App\Tests\BaseTestCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\Entity\UserId;
use App\User\Infrastructure\Persistence\UserOrm;
use App\User\Infrastructure\UserDoctrineRepositoryAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;

class UserDoctrineRepositoryAdapterTest extends BaseTestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private EntityRepository&MockObject $repositoryMock;
    private UserDoctrineRepositoryAdapter $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repositoryMock = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(UserOrm::class)
            ->willReturn($this->repositoryMock);

        $this->repository = new UserDoctrineRepositoryAdapter($this->entityManager);
    }


    public function testUserDoctrineRepositoryAdapterFind(): void
    {
        $id = UserId::generate();

        $expected = User::fromPrimitives([
            'id' => $id->value(),
            'name' => 'Siroko',
            'email' => 'user@siroko.com',
        ]);

        $expectedOrm = new UserOrm(
            id: $expected->id(),
            name: $expected->name(),
            email: $expected->email(),
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn($expectedOrm);

        $user = $this->repository->find($id);

        $this->assertEquals($expected, $user);
    }

    public function testUserDoctrineRepositoryAdapterFindNotFound(): void
    {
        $id = UserId::generate();

        $this->repositoryMock
            ->expects($this->once())
            ->method('find')
            ->with($id->value())
            ->willReturn(null);

        $user = $this->repository->find($id);

        $this->assertNull($user);
    }

    public function testUserDoctrineRepositoryAdapterSave(): void
    {
        $user = User::create([
            'name' => 'Siroko',
            'email' => 'user@siroko.com',
        ]);

        $expectedOrm = new UserOrm(
            id: $user->id(),
            name: $user->name(),
            email: $user->email(),
        );

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->callback(function (UserOrm $actual) use ($expectedOrm) {
                return $actual->id() === $expectedOrm->id() &&
                    $actual->name() === $expectedOrm->name();
            }));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $result = $this->repository->save($user);

        $this->assertSame($user, $result);
    }

    public function testUserDoctrineRepositoryAdapterFindByEmail(): void
    {
        $email = new UserEmail('user@siroko.com');

        $expected = User::fromPrimitives([
            'id' => '123e4567-e89b-12d3-a456-426614174000',
            'name' => 'Siroko',
            'email' => $email->value(),
        ]);

        $expectedOrm = new UserOrm(
            id: $expected->id(),
            name: $expected->name(),
            email: $expected->email(),
        );

        $this->repositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($expectedOrm);

        $user = $this->repository->findByEmail($email);

        $this->assertEquals($expected, $user);
    }

    public function testUserDoctrineRepositoryAdapterFindByEmailNotFound(): void
    {
        $email = new UserEmail('user@siroko.com');

        $this->repositoryMock
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn(null);

        $user = $this->repository->findByEmail($email);

        $this->assertNull($user);
    }
}
