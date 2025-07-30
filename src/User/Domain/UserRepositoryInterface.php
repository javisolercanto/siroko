<?php

declare(strict_types=1);

namespace App\User\Domain;

use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\Entity\UserId;

interface UserRepositoryInterface
{
    /**
     * Find user by id if not found return null
     * 
     * @param UserId $id
     * @return ?User
     */
    public function find(UserId $id): ?User;

    /**
     * Find user by email if not found return null
     * 
     * @param UserEmail $email
     * @return ?User
     */
    public function findByEmail(UserEmail $email): ?User;

    /**
     * Save user
     * 
     * @param User $user
     * @return User
     */
    public function save(User $user): User;
}
