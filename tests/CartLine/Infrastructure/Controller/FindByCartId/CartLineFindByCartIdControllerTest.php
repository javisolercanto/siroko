<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Find;

use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use App\CartLine\Application\CartLineResponse;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Cart\Application\CartResponse;
use App\Cart\Application\Find\CartFindQuery;
use App\CartLine\Infrastructure\Controller\FindByCartId\CartLineFindByCartIdController;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CartLineFindByCartIdControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private CartLineFindByCartIdController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(CartLineFindByCartIdController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testCartLineFindByCartIdControllerFind(): void
    {
        $this->simulateAuth();

        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';
        $ownerId = '123e4567-e89b-12d3-a456-426614174000';
        $productId = '019850bc-0cc9-74f3-886f-ff13031f2fc8';
        $cartId = '0198521d-7868-7d8f-9737-9789a7e1604c';
        $quantity = 1;

        $cartResponse = new CartResponse(
            id: $cartId,
            owner_id: $ownerId,
            processed: false,
        );

        $cartLineResponse = new CartLineResponse(
            id: $id,
            owner_id: $ownerId,
            cart_id: $cartId,
            product_id: $productId,
            quantity: $quantity
        );

        $this->queryBus
            ->expects($this->exactly(2))
            ->method('ask')
            ->willReturnCallback(function ($query) use ($cartResponse, $cartLineResponse) {
                if ($query instanceof CartFindQuery) {
                    return $cartResponse;
                }

                return $cartLineResponse;
            })
            ->willReturnOnConsecutiveCalls(
                $cartResponse,
                $cartLineResponse,
            );

        $httpResponse = $this->controller->findByCartId(
            id: $cartId,
            bus: $this->queryBus,
        );

        $this->assertInstanceOf(JsonResponse::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());

        $data = json_decode($httpResponse->getContent(), true);

        $this->assertEquals($id, $data['id']);
        $this->assertEquals($ownerId, $data['owner_id']);
        $this->assertEquals($cartId, $data['cart_id']);
        $this->assertEquals($productId, $data['product_id']);
        $this->assertEquals($quantity, $data['quantity']);
    }

    public function testCartLineFindByCartIdControllerFindNotFound(): void
    {
        $this->simulateAuth();

        $cartId = '01984fd5-150d-7309-8816-eb1b106262bd';

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $cartId))
            ->willReturn(null);

        $httpResponse = $this->controller->findByCartId(
            id: $cartId,
            bus: $this->queryBus,
        );

        $this->assertEquals(Response::HTTP_NOT_FOUND, $httpResponse->getStatusCode());
    }

    public function testCartLineFindByCartIdControllerFindUnauthorized(): void
    {
        $cartId = '01984fd5-150d-7309-8816-eb1b106262bd';

        $this->queryBus
            ->expects($this->never())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $cartId))
            ->willReturn(null);

        $httpResponse = $this->controller->findByCartId(
            id: $cartId,
            bus: $this->queryBus,
        );

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $httpResponse->getStatusCode());
    }

    public function testCartLineFindByCartIdControllerFindForbidden(): void
    {
        $this->simulateAuth(id: UuidValueObject::generate()->value());

        $ownerId = '123e4567-e89b-12d3-a456-426614174000';
        $cartId = '0198521d-7868-7d8f-9737-9789a7e1604c';

        $cartResponse = new CartResponse(
            id: $cartId,
            owner_id: $ownerId,
            processed: false,
        );

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $cartId))
            ->willReturn($cartResponse);

        $httpResponse = $this->controller->findByCartId(
            id: $cartId,
            bus: $this->queryBus,
        );

        $this->assertEquals(Response::HTTP_FORBIDDEN, $httpResponse->getStatusCode());
    }
}
