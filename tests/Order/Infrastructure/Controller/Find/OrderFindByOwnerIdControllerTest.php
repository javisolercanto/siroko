<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure\Controller\FindByOwnerId;

use App\Order\Application\OrderResponse;
use App\Order\Infrastructure\Controller\FindByOwnerId\OrderFindByOwnerIdController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderFindByOwnerIdControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private OrderFindByOwnerIdController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(OrderFindByOwnerIdController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testOrderFindByOwnerIdControllerTestFindByOwnerId(): void
    {
        $this->simulateAuth();

        $id = '0198521d-7868-7d8f-9737-9789a7e1604c';
        $ownerId = '123e4567-e89b-12d3-a456-426614174000';
        $cartId = '01984fd5-150d-7309-8816-eb1b106262bd';
        $date = (new \DateTime())->format('c');

        $response = new OrderResponse(
            id: $id,
            owner_id: $ownerId,
            cart_id: $cartId,
            date: $date,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->ownerId === $ownerId))
            ->willReturn($response);

        $httpResponse = $this->controller->findByOwnerId($this->queryBus, $ownerId);

        $this->assertInstanceOf(JsonResponse::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());

        $data = json_decode($httpResponse->getContent(), true);

        $this->assertEquals($id, $data['id']);
        $this->assertEquals($ownerId, $data['owner_id']);
        $this->assertEquals($cartId, $data['cart_id']);
        $this->assertEquals($date, $data['date']);
    }

    public function testOrderFindByOwnerIdControllerTestFindByOwnerIdNotFound(): void
    {
        $ownerId = '01984fd5-150d-7309-8816-eb1b106262bd';

        $this->simulateAuth(id: $ownerId);

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->ownerId === $ownerId))
            ->willReturn([]);

        $httpResponse = $this->controller->findByOwnerId($this->queryBus, $ownerId);

        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());

        $data = json_decode($httpResponse->getContent(), true);

        $this->assertEmpty($data);
    }
}
