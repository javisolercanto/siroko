<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure\Controller\FindAll;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductFindAllControllerIntegrationTest extends BaseWebTestCase
{
    public function testProductFindAllControllerIntegrationFindAll(): void
    {
        $this->authRequest('GET', '/api/products');

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
    }
}
