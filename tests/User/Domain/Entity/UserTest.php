<?php

declare(strict_types=1);

namespace App\Tests\User\Domain\Entity;

use App\Tests\BaseTestCase;
use App\User\Domain\Entity\User;

class UserTest extends BaseTestCase
{
    public function testUserCreate(): void
    {
        $primitives = [
            'name' => 'Siroko',
            'email' => 'user@siroko.com',
        ];

        $user = User::create($primitives);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($primitives['name'], $user->name());
        $this->assertEquals($primitives['email'], $user->email());
        $this->assertNotNull($user->id());
    }

    public function testUserPrimitives(): void
    {
        $primitives = [
            'id' => '0198405b-4f03-737c-950d-d14e39290e5c',
            'name' => 'Siroko',
            'email' => 'user@siroko.com',
        ];

        $user = User::fromPrimitives($primitives);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($primitives['name'], $user->name());
        $this->assertEquals($primitives['email'], $user->email());
        $this->assertEquals($primitives['id'], $user->id());

        $this->assertEquals($primitives, $user->toPrimitives());
    }
}
