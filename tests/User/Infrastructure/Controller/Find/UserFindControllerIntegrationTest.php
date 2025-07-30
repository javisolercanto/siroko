<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure\Controller\Find;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserFindControllerIntegrationTest extends BaseWebTestCase
{
    public function testUserFindControllerIntegrationFind(): void
    {

        $this->authRequest('GET', '/api/user/123e4567-e89b-12d3-a456-426614174000');

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $data['id']);
        $this->assertEquals('Siroko', $data['name']);
        $this->assertEquals('user@siroko.com', $data['email']);
    }

    public function testUserFindControllerIntegrationFindNotFound(): void
    {
        $this->authRequest('GET', '/api/user/01984fd5-150d-7309-8816-eb1b106262ed');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $this->statusCode());
    }
}
