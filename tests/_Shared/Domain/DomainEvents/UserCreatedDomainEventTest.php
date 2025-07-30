<?php

declare(strict_types=1);

namespace App\Tests\_Shared\Domain\DomainEvents;

use App\_Shared\Domain\DomainEvents\UserCreatedDomainEvent;
use App\Tests\BaseTestCase;

class UserCreatedDomainEventTest extends BaseTestCase
{
    public function testUserCreatedDomainEventNew(): void
    {
        $id = '123e4567-e89b-12d3-a456-426614174000';

        $event = new UserCreatedDomainEvent(userId: $id);

        $this->assertInstanceOf(UserCreatedDomainEvent::class, $event);
        $this->assertSame($id, $event->aggregateId());
    }
}