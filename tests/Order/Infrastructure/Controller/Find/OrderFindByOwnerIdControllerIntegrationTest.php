<?php

declare(strict_types=1);

namespace App\Tests\Order\Infrastructure\Controller\FindByOwnerId;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class OrderFindByOwnerIdControllerIntegrationTest extends BaseWebTestCase
{
    public function testOrderFindByOwnerIdControllerIntegrationFindByOwnerId(): void
    {
        $this->authRequest('GET', '/api/order');

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertEquals('01985828-6df1-7a5b-aa5b-9af694856f30', $data[0]['id']);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $data[0]['owner_id']);
        $this->assertEquals('0198521d-7868-7d8f-9737-9789a7e1604d', $data[0]['cart_id']);
    }

    public function testOrderFindByOwnerIdControllerIntegrationFindByOwnerIdUnauthorized(): void
    {
        $this->request('GET', '/api/order', auth: $this->invalidToken());

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->statusCode());
    }
}
