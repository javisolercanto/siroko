<?php

declare(strict_types=1);

namespace App\User\Infrastructure;

use App\_Shared\Infrastructure\RepositoryAdapter;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\Entity\UserId;
use App\User\Domain\UserRepositoryInterface;
use App\User\Infrastructure\Persistence\UserOrm;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends RepositoryAdapter<UserOrm>
 */
class UserDoctrineRepositoryAdapter extends RepositoryAdapter implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, UserOrm::class);
    }

    public function find(UserId $id): ?User
    {
        /** @var UserOrm|null */
        $user = $this->repository->find($id->value());
        if ($user === null) {
            return null;
        }

        return $this->toDomain($user);
    }

    public function findByEmail(UserEmail $email): ?User
    {
        /** @var UserOrm|null */
        $user = $this->repository->findOneBy(['email' => $email->value()]);
        if ($user === null) {
            return null;
        }

        return $this->toDomain($user);
    }

    public function save(User $user): User
    {
        $persitence = $this->toPersistence($user);

        $this->entityManager->persist($persitence);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Transform a UserOrm object into a User object.
     *
     * @param UserOrm $object
     * @return User
     */
    protected function toDomain(object $object): User
    {
        return User::fromPrimitives([
            'id' => $object->id(),
            'name' => $object->name(),
            'email' => $object->email(),
        ]);
    }

    /**
     * Transform a User object into a UserOrm object.
     *
     * @param User $object
     * @return UserOrm
     */
    protected function toPersistence(object $object): UserOrm
    {
        return new UserOrm(
            id: $object->id(),
            name: $object->name(),
            email: $object->email(),
        );
    }
}
