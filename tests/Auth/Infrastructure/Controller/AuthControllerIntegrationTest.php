<?php

declare(strict_types=1);

namespace App\Tests\Auth\Infrastructure\Controller;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AuthControllerIntegrationTest extends BaseWebTestCase
{
    public function testAuthControllerIntegrationAuth(): void
    {
        $this->request('POST', '/api/login', auth: null, params: [
            'email' => 'user@siroko.com',
        ]);

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertArrayHasKey('token', $data);
        $this->assertIsString($data['token']);
    }

    public function testAuthControllerIntegrationBadRequest(): void
    {
        $this->request('POST', '/api/login', auth: null, params: [
            'user' => 'user@siroko.com',
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->statusCode());
    }

    public function testAuthControllerIntegrationUnauthorized(): void
    {
        $this->request('POST', '/api/login', auth: null, params: [
            'email' => 'error@siroko.com',
        ]);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->statusCode());
    }
}
