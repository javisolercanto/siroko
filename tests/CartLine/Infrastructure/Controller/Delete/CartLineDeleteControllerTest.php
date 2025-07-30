<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Delete;

use App\_Shared\Message\Command\Domain\CommandBus;
use App\CartLine\Infrastructure\Controller\Delete\CartLineDeleteController;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;

class CartLineDeleteControllerTest extends BaseWebTestCase
{
    private CommandBus&MockObject $commandBus;
    private CartLineDeleteController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartLineDeleteController::class);
        $this->commandBus = $this->createMock(CommandBus::class);
    }

    public function testCartLineDeleteControllerDelete(): void
    {
        $this->simulateAuth();

        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $httpResponse = $this->controller->delete(
            id: $id,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_ACCEPTED, $httpResponse->getStatusCode());
    }

    public function testCartLineDeleteControllerError(): void
    {
        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $httpResponse = $this->controller->Delete(
            id: $id,
            bus: $this->commandBus,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }
}
