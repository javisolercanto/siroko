<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Controller\Find;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CartFindByOwnerIdControllerIntegrationTest extends BaseWebTestCase
{
    public function testCartFindByOwnerIdControllerIntegrationFind(): void
    {
        $this->authRequest('GET', '/api/cart');

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertEquals('0198521d-7868-7d8f-9737-9789a7e1604d', $data['id']);
        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $data['owner_id']);
    }
}
