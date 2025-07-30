<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Create;

use App\_Shared\Message\Command\Domain\CommandBus;
use App\CartLine\Infrastructure\Controller\Create\CartLineCreateController;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartLineCreateControllerTest extends BaseWebTestCase
{
    private CommandBus&MockObject $commandBus;
    private CartLineCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartLineCreateController::class);
        $this->commandBus = $this->createMock(CommandBus::class);
    }

    public function testCartLineCreateControllerCreate(): void
    {
        $this->simulateAuth();

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"cart_id":"019855ee-74c0-73a8-83b0-0b91ee63342c","product_id":"019850bc-0cc9-74f3-886f-ff13031f2fc8","quantity":1}');

        $httpResponse = $this->controller->create(
            request: $request,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_ACCEPTED, $httpResponse->getStatusCode());
    }

    public function testCartLineCreateControllerBadRequest(): void
    {
        $this->simulateAuth();

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"cart_id":"019855ee-74c0-73a8-83b0-0b91ee63342c","quantity":1}');

        $httpResponse = $this->controller->create(
            request: $request,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $httpResponse->getStatusCode());
    }

    public function testCartLineCreateControllerCreateError(): void
    {
        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $request = $this->createMock(Request::class);

        $httpResponse = $this->controller->create(
            request: $request,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }
}
