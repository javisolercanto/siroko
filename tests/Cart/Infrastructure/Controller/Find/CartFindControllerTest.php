<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Controller\Find;

use App\Cart\Application\CartResponse;
use App\Cart\Infrastructure\Controller\Find\CartFindController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CartFindControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private CartFindController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartFindController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testCartFindControllerTestFind(): void
    {
        $id = '0198521d-7868-7d8f-9737-9789a7e1604d';
        $ownerId = '01984fd5-150d-7309-8816-eb1b106262be';
        $processed = false;

        $response = new CartResponse(
            id: $id,
            owner_id: $ownerId,
            processed: $processed,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $id))
            ->willReturn($response);

        $httpResponse = $this->controller->find($this->queryBus, $id);

        $this->assertInstanceOf(JsonResponse::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());

        $data = json_decode($httpResponse->getContent(), true);

        $this->assertEquals($id, $data['id']);
        $this->assertEquals($ownerId, $data['owner_id']);
        $this->assertEquals($processed, $data['processed']);
    }

    public function testCartFindControllerTestFindNotFound(): void
    {
        $id = '01984fd5-150d-7309-8816-eb1b106262bd';

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $id))
            ->willReturn(null);

        $httpResponse = $this->controller->find($this->queryBus, $id);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $httpResponse->getStatusCode());
    }
}
