<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure\Controller\Create;

use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Order\Infrastructure\Controller\Create\OrderCreateController;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;

class OrderCreateControllerTest extends BaseWebTestCase
{
    private CommandBus&MockObject $commandBus;
    private QueryBus&MockObject $queryBus;
    private OrderCreateController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(OrderCreateController::class);
        $this->commandBus = $this->createMock(CommandBus::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testOrderCreateControllerCreate(): void
    {
        $this->simulateAuth();

        $this->commandBus
            ->expects($this->once())
            ->method('dispatch');

        $queryBus = $this->getContainer()->get(QueryBus::class);

        $cartId = '0198521d-7868-7d8f-9737-9789a7e1604d';

        $httpResponse = $this->controller->create(
            commandBus: $this->commandBus,
            queryBus: $queryBus,
            id: $cartId,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_ACCEPTED, $httpResponse->getStatusCode());
    }

    public function testOrderCreateControllerNotFound(): void
    {
        $this->simulateAuth();

        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $this->queryBus
            ->expects($this->once())
            ->method('ask');

        $cartId = '0198521d-7868-7d8f-9737-9789a7e1605d';

        $httpResponse = $this->controller->create(
            commandBus: $this->commandBus,
            queryBus: $this->queryBus,
            id: $cartId,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $httpResponse->getStatusCode());
    }

    public function testOrderCreateControllerUnauthorized(): void
    {
        $this->commandBus
            ->expects($this->never())
            ->method('dispatch');

        $this->queryBus
            ->expects($this->never())
            ->method('ask');

        $cartId = '01984fd5-150d-7309-8816-eb1b106262bd';

        $httpResponse = $this->controller->create(
            commandBus: $this->commandBus,
            queryBus: $this->queryBus,
            id: $cartId,
        );

        $this->assertInstanceOf(Response::class, $httpResponse);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }
}
