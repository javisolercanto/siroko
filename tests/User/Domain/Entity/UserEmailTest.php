<?php

declare(strict_types=1);

namespace App\Tests\User\Domain\Entity;

use App\Tests\BaseTestCase;
use App\User\Domain\Entity\UserEmail;
use App\User\Domain\Exception\UserInvalidEmailException;

class UserEmailTest extends BaseTestCase
{
    public function testNewUserEmail()
    {
        $expected = 'user@siroko.com';

        $email = new UserEmail($expected);

        $this->assertInstanceOf(UserEmail::class, $email);
        $this->assertEquals($expected, $email->value());
    }

    public function testInvalidEmail()
    {
        $this->expectException(UserInvalidEmailException::class);
        $this->expectExceptionMessage('Invalid email');
        new UserEmail('email');
    }
}
