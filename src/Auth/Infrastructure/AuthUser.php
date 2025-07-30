<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

class AuthUser implements JWTUserInterface
{
    public function __construct(
        public readonly string $id,
    ) {}

    public function getUserIdentifier(): string
    {
        assert($this->id !== '', 'Id should not be empty');

        return $this->id;
    }

    /**
     * @param string $username
     * @param array<string, mixed> $payload
     */
    public static function createFromPayload($username, array $payload): self
    {
        return new self(id: $username);
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void {}
}
