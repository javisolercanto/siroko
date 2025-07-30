<?php

declare(strict_types=1);

namespace App\Tests\Auth\Infrastructure\Controller;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthControllerIntegrationTest extends BaseWebTestCase
{
    public function testCheckAuthControllerIntegrationAuth(): void
    {
        $this->authRequest('GET', '/api/check');

        $this->assertEquals(Response::HTTP_OK, $this->statusCode());

        $data = json_decode($this->response()->getContent(), true);

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $data['id']);
        $this->assertEquals('Siroko', $data['name']);
        $this->assertEquals('user@siroko.com', $data['email']);
    }

    public function testCheckAuthControllerIntegrationUnauthorized(): void
    {
        $this->request('GET', '/api/check');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->statusCode());

        $this->request('GET', '/api/check', auth: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiYWRtaW4iOnRydWUsImlhdCI6MTUxNjIzOTAyMn0.KMUFsIDTnFmyG3nMiGM6H9FNFUROf3wh7SmqJp-QV30');
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $this->statusCode());
    }
}
