<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Create;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartLineEditControllerIntegrationTest extends BaseWebTestCase
{
    public function testCartLineEditControllerIntegrationCreate(): void
    {
        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $params = [
            'quantity' => 1,
        ];

        $this->authRequest('PUT', '/api/cart-line/' . $id, $params);
        $this->assertEquals(Response::HTTP_ACCEPTED, $this->statusCode());
    }

    public function testCartLineEditControllerIntegrationBadRequest(): void
    {
        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $params = [
            'product_id' => '01984fd5-150d-7309-8816-eb1b106262ed',
        ];

        $this->authRequest('PUT', '/api/cart-line/' . $id, $params);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->statusCode());
    }
}
