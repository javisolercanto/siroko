<?php

declare(strict_types=1);

namespace App\Tests\User\Domain;

use App\_Shared\Message\Event\Domain\DomainEventBus;
use App\Tests\BaseTestCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserId;
use App\User\Domain\UserApplicationService;
use App\User\Domain\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;

class UserApplicationServiceTest extends BaseTestCase
{
    private UserApplicationService $applicationService;
    private UserRepositoryInterface&MockObject $repository;
    private DomainEventBus&MockObject $eventBus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(UserRepositoryInterface::class);
        $this->eventBus = $this->createMock(DomainEventBus::class);

        $this->applicationService = new UserApplicationService(
            repository: $this->repository,
            eventBus: $this->eventBus,
        );
    }

    public function testUserApplicationServiceFind(): void
    {
        $user = User::create(['name' => 'Siroko', 'email' => 'user@siroko.com']);
        $userId = new UserId($user->id());

        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($userId)
            ->willReturn($user);

        $result = $this->applicationService->find($userId);

        $this->assertSame($user, $result);
    }

    public function testUserApplicationServiceSave(): void
    {
        $user = User::create(['name' => 'Siroko', 'email' => '003@siroko.com']);

        $this->eventBus
            ->expects($this->once())
            ->method('publish');

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $result = $this->applicationService->save($user);

        $this->assertSame($user, $result);
    }
}
