<?php

declare(strict_types=1);

namespace App\Tests\_Shared\Message\AggregateRoot\Entity;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\_Shared\Message\AggregateRoot\Exception\InvalidUuidException;
use App\Tests\BaseTestCase;

class UuidValueObjectTest extends BaseTestCase
{
    public function testGenerateUuidValueObject(): void
    {
        $uuid = UuidValueObject::generate();
        $this->assertInstanceOf(UuidValueObject::class, $uuid);
    }

    public function testToStringUuidValueObject(): void
    {
        $uuid = UuidValueObject::generate();
        $this->assertIsString($uuid->__toString());
    }

    public function testGetValueUuidValueObject(): void
    {
        $uuid = UuidValueObject::generate();
        $this->assertIsString($uuid->value());
    }

    public function testCreateUuidValueObject(): void
    {
        $expected = '123e4567-e89b-12d3-a456-426655440000';

        $uuid = new UuidValueObject($expected);
        $this->assertInstanceOf(UuidValueObject::class, $uuid);
        $this->assertEquals($expected, $uuid->value());
    }

    public function testCreateWithInvalidUuidUuidValueObject(): void
    {
        $this->expectException(InvalidUuidException::class);
        new UuidValueObject('invalid-uuid');
    }

    public function testCreateWithEmptyUuidUuidValueObject(): void
    {
        $this->expectException(InvalidUuidException::class);
        new UuidValueObject('');
    }

    public function testCreateUuidValueObjectIsValid(): void
    {
        $uuid = UuidValueObject::generate();
        $this->assertTrue(UuidValueObject::isValid($uuid->value()));
    }

    public function testCreateUuidValueObjectIsNotValid(): void
    {
        $this->assertFalse(UuidValueObject::isValid('siroko'));
    }
}
