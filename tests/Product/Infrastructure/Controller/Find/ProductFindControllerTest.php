<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure\Controller\Find;

use App\Product\Application\ProductResponse;
use App\Product\Infrastructure\Controller\Find\ProductFindController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductFindControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private ProductFindController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(ProductFindController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testProductFindControllerTestFind(): void
    {
        $id = '01984fd5-150d-7309-8816-eb1b106262bf';
        $response = new ProductResponse(
            id: $id,
            name: 'Maillot',
            price: 50.0,
            stock: 10,
            available_stock: 10,
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
        $this->assertEquals($response->name, $data['name']);
        $this->assertEquals($response->price, $data['price']);
        $this->assertEquals($response->stock, $data['stock']);
        $this->assertEquals($response->available_stock, $data['available_stock']);
    }

    public function testProductFindControllerTestFindNotFound(): void
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
