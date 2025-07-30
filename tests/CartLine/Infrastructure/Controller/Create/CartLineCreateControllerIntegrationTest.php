<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Create;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartLineCreateControllerIntegrationTest extends BaseWebTestCase
{
    public function testCartLineCreateControllerIntegrationCreate(): void
    {
        $params = [
            'product_id' => '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            'cart_id' => '01984fd5-150d-7309-8816-eb1b106262ed',
            'quantity' => 1
        ];

        $this->authRequest('POST', '/api/cart-line', $params);
        $this->assertEquals(Response::HTTP_ACCEPTED, $this->statusCode());
    }

    public function testCartLineCreateControllerIntegrationCreateProductNotFound(): void
    {
        $params = [
            'product_id' => '01984fd5-150d-7309-8816-eb1b106262ed',
            'cart_id' => '01984fd5-150d-7309-8816-eb1b106262ed',
            'quantity' => 1
        ];

        $this->authRequest('POST', '/api/cart-line', $params);
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $this->statusCode());
    }

    public function testCartLineCreateControllerIntegrationBadRequest(): void
    {
        $params = [
            'product_id' => '01984fd5-150d-7309-8816-eb1b106262ed',
        ];

        $this->authRequest('POST', '/api/cart-line', $params);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->statusCode());
    }
}
