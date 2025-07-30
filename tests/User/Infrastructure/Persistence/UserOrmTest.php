<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure\Persistence;

use App\Tests\BaseTestCase;
use App\User\Infrastructure\Persistence\UserOrm;

class UserOrmTest extends BaseTestCase
{
    public function testUserOrmNew(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';
        $name = 'Siroko';
        $email = 'user@siroko.com';

        $userOrm = new UserOrm(id: $id, name: $name, email: $email);

        $this->assertSame($id, $userOrm->id());
        $this->assertSame($name, $userOrm->name());
        $this->assertSame($email, $userOrm->email());
    }
}
