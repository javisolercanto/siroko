<?php

declare(strict_types=1);

namespace App\Tests\User\Application;

use App\Tests\BaseTestCase;
use App\User\Application\Create\UserCreateCommand;

class UserCreateCommandTest extends BaseTestCase
{
    public function testUserCreateCommandFromPrimitives(): void
    {
        $name = 'Siroko';
        $email = '001@siroko.com';

        $data = ['name' => $name, 'email' => $email];

        $command = UserCreateCommand::fromPrimitives(aggregateId: '', primitives: $data);

        $this->assertInstanceOf(UserCreateCommand::class, $command);
        $this->assertSame($name, $command->name);
        $this->assertSame($email, $command->email);
        $this->assertSame($data, $command->toPrimitives());
        $this->assertSame('', $command->aggregateId());
    }

    public function testUserCreateCommandToPrimitives(): void
    {
        $name = 'Siroko';
        $email = '002@siroko.com';

        $command = new UserCreateCommand(name: $name, email: $email);

        $expected = ['name' => $name, 'email' => $email];

        $this->assertSame($expected, $command->toPrimitives());
    }
}
