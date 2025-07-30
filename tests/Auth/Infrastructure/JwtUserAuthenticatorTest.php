<?php

declare(strict_types=1);

namespace App\Tests\Auth\Infrastructure;

use App\Auth\Infrastructure\JWTUserAuthenticator;
use App\_Shared\Message\AggregateRoot\Entity\UuidValueObject;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidPayloadException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\InvalidTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class JWTUserAuthenticatorTest extends TestCase
{
    private JWTUserAuthenticator $authenticator;
    private JWTManager&MockObject $jwtManager;
    private TokenExtractorInterface&MockObject $tokenExtractor;

    protected function setUp(): void
    {
        $this->jwtManager = $this->createMock(JWTManager::class);
        $this->tokenExtractor = $this->createMock(TokenExtractorInterface::class);

        $this->authenticator = new class($this->jwtManager, $this->tokenExtractor) extends JWTUserAuthenticator {
            private JWTManager $jwtManager;
            private TokenExtractorInterface $tokenExtractor;

            public function __construct(JWTManager $jwtManager, TokenExtractorInterface $tokenExtractor)
            {
                $this->jwtManager = $jwtManager;
                $this->tokenExtractor = $tokenExtractor;
            }

            protected function getJwtManager(): JWTManager
            {
                return $this->jwtManager;
            }

            protected function getTokenExtractor(): TokenExtractorInterface
            {
                return $this->tokenExtractor;
            }
        };
    }

    public function testSupportsReturnsFalseWhenNoToken(): void
    {
        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn(false);

        $this->assertFalse($this->authenticator->supports($request));
    }

    public function testSupportsReturnsFalseWhenPayloadIsEmpty(): void
    {
        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtManager->method('parse')->willReturn([]);

        $this->assertFalse($this->authenticator->supports($request));
    }

    public function testSupportsReturnsFalseWhenSubIsInvalidUuid(): void
    {
        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtManager->method('parse')->willReturn(['sub' => 'invalid-uuid']);

        $this->assertFalse($this->authenticator->supports($request));
    }

    public function testSupportsReturnsTrueWhenTokenIsValid(): void
    {
        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');
        $uuid = UuidValueObject::generate()->value();
        $this->jwtManager->method('parse')->willReturn(['sub' => $uuid]);

        $this->assertTrue($this->authenticator->supports($request));
    }

    public function testAuthenticateThrowsIfNoToken(): void
    {
        $this->expectException(\LogicException::class);

        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn(false);

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateThrowsOnEmptyPayload(): void
    {
        $this->expectException(InvalidTokenException::class);

        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtManager->method('parse');

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateThrowsOnExpiredToken(): void
    {
        $this->expectException(ExpiredTokenException::class);

        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');

        $exception = new JWTDecodeFailureException('expired_token', JWTDecodeFailureException::EXPIRED_TOKEN);
        $this->jwtManager->method('parse')->willThrowException($exception);

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateThrowsOnInvalidToken(): void
    {
        $this->expectException(InvalidTokenException::class);

        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');

        $exception = new JWTDecodeFailureException('invalid_token', JWTDecodeFailureException::INVALID_TOKEN);
        $this->jwtManager->method('parse')->willThrowException($exception);

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateThrowsOnMissingUserIdClaim(): void
    {
        $this->expectException(InvalidPayloadException::class);

        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtManager->method('parse')->willReturn(['sub' => UuidValueObject::generate()->value()]);
        $this->jwtManager->method('getUserIdClaim')->willReturn('sub_id');

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateReturnsPassport(): void
    {
        $uuid = UuidValueObject::generate()->value();

        $request = new Request();
        $this->tokenExtractor->method('extract')->willReturn('token');
        $this->jwtManager->method('parse')->willReturn(['sub' => $uuid]);
        $this->jwtManager->method('getUserIdClaim')->willReturn('sub');

        $passport = $this->authenticator->authenticate($request);

        $this->assertEquals('token', $passport->getAttribute('token'));
        $this->assertEquals(['sub' => $uuid], $passport->getAttribute('payload'));
    }
}