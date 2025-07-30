<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Controller\Find;

use App\Cart\Application\CartResponse;
use App\Cart\Infrastructure\Controller\FindByOwnerId\CartFindByOwnerIdController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;

class CartFindByOwnerIdControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private CartFindByOwnerIdController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartFindByOwnerIdController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testCartFindByOwnerIdControllerTestFind(): void
    {
        $this->simulateAuth();

        $id = '0198521d-7868-7d8f-9737-9789a7e1604d';
        $ownerId = '123e4567-e89b-12d3-a456-426614174000';

        $response = new CartResponse(
            id: $id,
            owner_id: $ownerId,
            processed: false,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with()
            ->willReturn($response);

        $httpResponse = $this->controller->findByOwnerId($this->queryBus);

        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());
    }

    public function testCartFindByOwnerIdControllerTestFindNotFound(): void
    {
        $this->simulateAuth();

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with()
            ->willReturn(null);

        $httpResponse = $this->controller->findByOwnerId($this->queryBus);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $httpResponse->getStatusCode());
    }

    public function testCartFindByOwnerIdControllerTestFindUnauthorized(): void
    {
        $id = '0198521d-7868-7d8f-9737-9789a7e1604d';
        $ownerId = '01984fd5-150d-7309-8816-eb1b106262be';

        $response = new CartResponse(
            id: $id,
            owner_id: $ownerId,
            processed: true,
        );

        $this->queryBus
            ->expects($this->never())
            ->method('ask')
            ->with()
            ->willReturn($response);

        $httpResponse = $this->controller->findByOwnerId($this->queryBus);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }
}
