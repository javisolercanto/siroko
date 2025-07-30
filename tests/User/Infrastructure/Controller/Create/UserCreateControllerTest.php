<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure\Controller\Create;

use App\_Shared\Message\Command\Domain\CommandBus;
use App\Tests\BaseWebTestCase;
use App\User\Infrastructure\Controller\Create\UserCreateController;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCreateControllerTest extends BaseWebTestCase
{
    private CommandBus&MockObject $commandBus;
    private UserCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(UserCreateController::class);
        $this->commandBus = $this->createMock(CommandBus::class);
    }

    public function testUserCreateControllerCreate(): void
    {
        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"name":"Siroko", "email":"user@siroko.com"}');

        $httpResponse = $this->controller->create($this->commandBus, $request);

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_ACCEPTED, $httpResponse->getStatusCode());
    }

    public function testUserCreateControllerCreateError(): void
    {
        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"age":"20"}');

        $httpResponse = $this->controller->create($this->commandBus, $request);

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $httpResponse->getStatusCode());
    }
}
