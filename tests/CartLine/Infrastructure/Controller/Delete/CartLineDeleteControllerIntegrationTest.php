<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Infrastructure\Controller\Create;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartLineDeleteControllerIntegrationTest extends BaseWebTestCase
{
    public function testCartLineDeleteControllerIntegrationCreate(): void
    {
        $id = '019855ee-74c0-73a8-83b0-0b91ee63342c';

        $this->authRequest('DELETE', '/api/cart-line/' . $id);
        $this->assertEquals(Response::HTTP_ACCEPTED, $this->statusCode());
    }
}
