<?php

declare(strict_types=1);

namespace App\_Shared\Infrastructure;

use App\_Shared\Infrastructure\Persistence\BaseOrm;
use App\_Shared\Message\AggregateRoot\Entity\AggregateRoot;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use ReflectionObject;

/**
 * @template TEntity of object
 */
abstract class RepositoryAdapter
{
    /**
     * @var EntityRepository<TEntity>
     */
    protected readonly EntityRepository $repository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param class-string<TEntity> $entityClass
     */
    public function __construct(EntityManagerInterface $entityManager, string $entityClass)
    {
        /** @var EntityRepository<TEntity> $repo */
        $repo = $entityManager->getRepository($entityClass);
        $this->repository = $repo;
    }

    /**
     * Transform the Orm object into an Aggregate object
     * 
     * @param TEntity $object
     * @return object
     */
    abstract protected function toDomain(object $object): object;

    /**
     * Tranform the aggregate into an Orm object with the expected properties
     * 
     * @param object $object
     * @return TEntity
     */
    abstract protected function toPersistence(object $object): object;
}
