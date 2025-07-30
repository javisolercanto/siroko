<?php

declare(strict_types=1);

namespace App\Tests\User\Infrastructure\Controller\Find;

use App\User\Application\UserResponse;
use App\User\Infrastructure\Controller\Find\UserFindController;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserFindControllerTest extends BaseWebTestCase
{
    private QueryBus&MockObject $queryBus;
    private UserFindController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->client->getContainer()->get(UserFindController::class);
        $this->queryBus = $this->createMock(QueryBus::class);
    }

    public function testUserFindControllerTestFind(): void
    {
        $id = '01984fd5-150d-7309-8816-eb1b106262bd';
        $response = new UserResponse(
            id: $id,
            name: 'Siroko',
            email: 'user@siroko.com',
        );

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $id))
            ->willReturn($response);

        $httpResponse = $this->controller->find($this->queryBus, $id);

        $this->assertInstanceOf(JsonResponse::class, $httpResponse);
        $this->assertEquals(Response::HTTP_OK, $httpResponse->getStatusCode());

        $data = json_decode($httpResponse->getContent(), true);

        $this->assertEquals($id, $data['id']);
        $this->assertEquals('Siroko', $data['name']);
        $this->assertEquals('user@siroko.com', $data['email']);
    }

    public function testUserFindControllerTestFindNotFound(): void
    {
        $id = '01984fd5-150d-7309-8816-eb1b106262bd';

        $this->queryBus
            ->expects($this->once())
            ->method('ask')
            ->with($this->callback(fn($query) => $query->id === $id))
            ->willReturn(null);

        $httpResponse = $this->controller->find($this->queryBus, $id);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $httpResponse->getStatusCode());
    }
}
