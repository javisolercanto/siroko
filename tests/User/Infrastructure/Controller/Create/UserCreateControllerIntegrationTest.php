<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure\Controller\Create;

use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserCreateControllerIntegrationTest extends BaseWebTestCase
{
    public function testUserCreateControllerIntegrationCreate(): void
    {
        $params = [
            'name' => 'Siroko',
            'email' => '000@siroko.com',
        ];

        $this->authRequest('POST', '/api/user', $params);
        $this->assertEquals(Response::HTTP_ACCEPTED, $this->statusCode());
    }
}
