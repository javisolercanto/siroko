<?php

declare(strict_types=1);

namespace App\Tests;

use App\Auth\Infrastructure\AuthUser;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BaseWebTestCase extends WebTestCase
{
    protected ?KernelBrowser $client;
    protected Connection $connection;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = static::createClient(['environment' => 'test']);

        $this->em = $this->client->getContainer()->get(EntityManagerInterface::class);
        $this->connection = $this->em->getConnection();

        if (!$this->connection->isTransactionActive()) {
            $this->connection->beginTransaction();
        }
    }

    protected function response(): object
    {
        return $this->client->getResponse();
    }

    protected function statusCode(): int
    {
        return $this->response()->getStatusCode();
    }

    protected function request(string $method, string $path, ?string $auth = null, array $params = []): void
    {
        $this->_request($method, $path, $auth, $params);
    }

    protected function authRequest(string $method, string $path, array $params = []): void
    {
        $this->_request($method, $path, $this->validToken(), $params);
    }

    private function _request(string $method, string $path, ?string $auth = null, array $params = []): void
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($auth) {
            $headers['HTTP_Authorization'] = 'Bearer ' . $auth;
        }

        $this->client->request($method, $path, [], [], $headers, json_encode($params));
    }

    protected function auth(string $email): string
    {
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => $email,
        ]));

        $data = json_decode($this->response()->getContent(), true);
        $token = $data['token'];

        return $token;
    }

    protected function validToken(): string
    {
        $this->client->request('POST', '/api/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'user@siroko.com',
        ]));

        $data = json_decode($this->response()->getContent(), true);
        $token = $data['token'];

        return $token;
    }

    protected function invalidToken(): string
    {
        return 'invalid';
    }

    protected function simulateAuth(string $id = '123e4567-e89b-12d3-a456-426614174000'): void
    {
        $user = new AuthUser(id: $id);

        $token = new UsernamePasswordToken(
            user: $user,
            firewallName: 'main',
            roles: ['ROLE_USER'],
        );

        $tokenStorage = static::getContainer()->get(TokenStorageInterface::class);
        $tokenStorage->setToken($token);
    }

    protected function tearDown(): void
    {
        if ($this->connection->isTransactionActive()) {
            $this->connection->rollBack();
        }

        parent::tearDown();
    }
}
