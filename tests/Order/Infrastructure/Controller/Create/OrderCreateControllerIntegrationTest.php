<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure\Controller\Create;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class OrderCreateControllerIntegrationTest extends BaseWebTestCase
{
    public function testOrderCreateControllerIntegrationCreate(): void
    {
        $this->authRequest('POST', '/api/order/create-from-cart/0198521d-7868-7d8f-9737-9789a7e1604c');
        $this->assertEquals(Response::HTTP_ACCEPTED, $this->statusCode());
    }
}
