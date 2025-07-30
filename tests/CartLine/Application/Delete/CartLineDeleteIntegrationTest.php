<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application\Delete;

use App\CartLine\Application\Delete\CartLineDeleteCommand;
use App\CartLine\Application\Delete\CartLineDeleteCommandHandler;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineUnauthorizedException;
use App\Tests\BaseTestCase;

class CartLineDeleteIntegrationTest extends BaseTestCase
{
    public function testCartLineDeleteIntegration(): void
    {
        $this->expectNotToPerformAssertions();

        $command = new CartLineDeleteCommand(
            id: '019855ee-74c0-73a8-83b0-0b91ee63342c',
            ownerId: '123e4567-e89b-12d3-a456-426614174000'
        );

        $handler = static::getContainer()->get(CartLineDeleteCommandHandler::class);
        $handler->__invoke($command);
    }

    public function testCartLineDeleteIntegrationError(): void
    {
        $this->expectException(CartLineNotFoundException::class);

        $command = new CartLineDeleteCommand(
            id: '0198522d-8352-72a9-9c3e-8ae625aca59e',
            ownerId: '0198522d-8352-72a9-9c3e-8ae625aca59f'
        );

        $handler = static::getContainer()->get(CartLineDeleteCommandHandler::class);
        $handler->__invoke($command);
    }

    public function testCartLineDeleteIntegrationUnahutorized(): void
    {
        $this->expectException(CartLineUnauthorizedException::class);

        $command = new CartLineDeleteCommand(
            id: '019855ee-74c0-73a8-83b0-0b91ee63342c',
            ownerId: '0198522d-8352-72a9-9c3e-8ae625aca59f'
        );

        $handler = static::getContainer()->get(CartLineDeleteCommandHandler::class);
        $handler->__invoke($command);
    }
}
