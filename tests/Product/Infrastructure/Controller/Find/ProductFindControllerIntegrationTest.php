<?php

declare(strict_types=1);

namespace App\Tests\Product\Infrastructure\Controller\Find;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductFindControllerIntegrationTest extends BaseWebTestCase
{
    public function testProductFindControllerIntegrationFind(): void
    {
        $this->authRequest('GET', '/api/product/019850bc-0cc9-74f3-886f-ff13031f2fc8');

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertEquals('019850bc-0cc9-74f3-886f-ff13031f2fc8', $data['id']);
        $this->assertEquals('Maillot', $data['name']);
        $this->assertEquals(50.0, $data['price']);
        $this->assertEquals(10, $data['stock']);
        $this->assertEquals(10, $data['available_stock']);
    }

    public function testProductFindControllerIntegrationFindNotFound(): void
    {
        $this->authRequest('GET', '/api/product/01984fd5-150d-7309-8816-eb1b106262ed');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->statusCode());
    }
}
