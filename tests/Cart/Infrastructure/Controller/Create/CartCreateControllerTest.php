<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Controller\Create;

use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\FindByOwnerId\CartFindByOwnerIdQuery;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Infrastructure\Controller\Create\CartCreateController;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CartCreateControllerTest extends BaseWebTestCase
{
    private CommandBus&MockObject $commandBus;
    private QueryBus&MockObject $queryBus;
    private CartCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartCreateController::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testCartCreateControllerCreate(): void
    {
        $this->simulateAuth();

        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $cart = $this->createMock(Cart::class);

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->willReturn($cart);

        $request = $this->createMock(Request::class);

        $httpResponse = $this->controller->create($this->commandBus, $this->queryBus, $request);

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());
    }

    public function testCartCreateControllerCreatePersist(): void
    {
        $this->simulateAuth();

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $this->queryBus
            ->expects($this->exactly(2))
            ->method('ask')
            ->with(new CartFindByOwnerIdQuery(ownerId: '123e4567-e89b-12d3-a456-426614174000'))
            ->willReturnOnConsecutiveCalls(null, $this->createMock(Cart::class));

        $request = $this->createMock(Request::class);

        $httpResponse = $this->controller->create($this->commandBus, $this->queryBus, $request);

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());
    }

    public function testCartCreateControllerCreatePersistError(): void
    {
        $this->simulateAuth();

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $this->queryBus
            ->expects($this->exactly(2))
            ->method('ask')
            ->with(new CartFindByOwnerIdQuery(ownerId: '123e4567-e89b-12d3-a456-426614174000'))
            ->willReturnOnConsecutiveCalls(null, null);

        $request = $this->createMock(Request::class);

        $httpResponse = $this->controller->create($this->commandBus, $this->queryBus, $request);

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $httpResponse->getStatusCode());
    }

    public function testCartCreateControllerCreateError(): void
    {
        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $request = $this->createMock(Request::class);

        $httpResponse = $this->controller->create($this->commandBus, $this->queryBus, $request);

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }
}
