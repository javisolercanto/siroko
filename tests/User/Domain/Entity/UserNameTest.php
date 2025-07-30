<?php

declare(strict_types=1);

namespace App\Tests\User\Domain\Entity;

use App\Tests\BaseTestCase;
use App\User\Domain\Entity\UserName;
use App\User\Domain\Exception\UserInvalidNameException;

class UserNameTest extends BaseTestCase
{
    public function testNewUserName()
    {
        $expected = 'Siroko';

        $name = new UserName($expected);

        $this->assertInstanceOf(UserName::class, $name);
        $this->assertEquals($expected, $name->value());
    }

    public function testInvalidNameShort()
    {
        $this->expectException(UserInvalidNameException::class);
        $this->expectExceptionMessage('Name too short');
        new UserName('Si');
    }

    public function testInvalidNameLong()
    {
        $this->expectException(UserInvalidNameException::class);
        $this->expectExceptionMessage('Name too long');
        new UserName('Sirokoooooooooooooooooooooooooo');
    }

    public function testInvalidNameEmpty()
    {
        $this->expectException(UserInvalidNameException::class);
        $this->expectExceptionMessage('Name too short');
        new UserName('');
    }

    public function testInvalidNameWrongCharacters()
    {
        $this->expectException(UserInvalidNameException::class);
        $this->expectExceptionMessage('Invalid characters');
        new UserName('Siroko <3');
    }
}
