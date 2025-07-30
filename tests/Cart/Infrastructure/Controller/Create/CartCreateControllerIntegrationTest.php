<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Controller\Create;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartCreateControllerIntegrationTest extends BaseWebTestCase
{
    public function testCartCreateControllerIntegrationGet(): void
    {
        $this->authRequest('POST', '/api/cart');
        $this->assertEquals(Response::HTTP_OK, $this->statusCode());
    }
}
