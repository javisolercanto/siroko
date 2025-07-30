<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Edit;

use App\_Shared\Message\Command\Domain\CommandBus;
use App\CartLine\Infrastructure\Controller\Edit\CartLineEditController;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartLineEditControllerTest extends BaseWebTestCase
{
    private CommandBus&MockObject $commandBus;
    private CartLineEditController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartLineEditController::class);
        $this->commandBus = $this->createMock(CommandBus::class);
    }

    public function testCartLineEditControllerEdit(): void
    {
        $this->simulateAuth();

        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"quantity":1}');

        $httpResponse = $this->controller->edit(
            request: $request,
            id: $id,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_ACCEPTED, $httpResponse->getStatusCode());
    }

    public function testCartLineEditControllerBadRequest(): void
    {
        $this->simulateAuth();

        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"cart_id":"019855ee-74c0-73a8-83b0-0b91ee63342c"}');

        $httpResponse = $this->controller->edit(
            request: $request,
            id: $id,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $httpResponse->getStatusCode());
    }

    public function testCartLineEditControllerEditError(): void
    {
        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $request = $this->createMock(Request::class);

        $httpResponse = $this->controller->edit(
            request: $request,
            id: $id,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }
}
