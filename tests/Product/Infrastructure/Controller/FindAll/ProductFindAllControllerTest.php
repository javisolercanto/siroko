<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure\Controller\FindAll;

use App\Product\Application\ProductResponse;
use App\Product\Infrastructure\Controller\FindAll\ProductFindAllController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductFindAllControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private ProductFindAllController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(ProductFindAllController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testProductFindAllControllerTestFindAll(): void
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
            ->with()
            ->willReturn([$response]);

        $httpResponse = $this->controller->findAll($this->queryBus);

        $this->assertInstanceOf(JsonResponse::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());

        $data = json_decode($httpResponse->getContent(), true);

        $this->assertEquals($id, $data[0]['id']);
        $this->assertEquals($response->name, $data[0]['name']);
        $this->assertEquals($response->price, $data[0]['price']);
        $this->assertEquals($response->stock, $data[0]['stock']);
        $this->assertEquals($response->available_stock, $data[0]['available_stock']);
    }
}
