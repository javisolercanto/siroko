<?php

declare(strict_types=1);

namespace App\Tests\CartLine\Application\Edit;

use App\CartLine\Application\Edit\CartLineEditCommand;
use App\CartLine\Application\Edit\CartLineEditCommandHandler;
use App\CartLine\Domain\Exception\CartLineNotFoundException;
use App\CartLine\Domain\Exception\CartLineUnauthorizedException;
use App\Tests\BaseTestCase;

class CartLineEditIntegrationTest extends BaseTestCase
{
    public function testCartLineEditIntegration(): void
    {
        $this->expectNotToPerformAssertions();

        $command = new CartLineEditCommand(
            cartLineId: '019855ee-74c0-73a8-83b0-0b91ee63342c',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
            quantity: 1,
        );

        $handler = static::getContainer()->get(CartLineEditCommandHandler::class);
        $handler->__invoke($command);
    }

    public function testCartLineEditIntegrationError(): void
    {
        $this->expectException(CartLineNotFoundException::class);

        $command = new CartLineEditCommand(
            cartLineId: '0198522d-8352-72a9-9c3e-8ae625aca59e',
            ownerId: '0198522d-8352-72a9-9c3e-8ae625aca59f',
            quantity: 2,
        );

        $handler = static::getContainer()->get(CartLineEditCommandHandler::class);
        $handler->__invoke($command);
    }

    public function testCartLineEditIntegrationUnahutorized(): void
    {
        $this->expectException(CartLineUnauthorizedException::class);

        $command = new CartLineEditCommand(
            cartLineId: '019855ee-74c0-73a8-83b0-0b91ee63342c',
            ownerId: '0198522d-8352-72a9-9c3e-8ae625aca59f',
            quantity: 2,
        );

        $handler = static::getContainer()->get(CartLineEditCommandHandler::class);
        $handler->__invoke($command);
    }
}
