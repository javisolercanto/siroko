<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application\Process;

use App\Cart\Application\Process\CartProcessCommand;
use App\Cart\Application\Process\CartProcessCommandHandler;
use App\Tests\BaseTestCase;

class CartProcessIntegrationTest extends BaseTestCase
{
    public function testCartProcessIntegration(): void
    {
        $this->expectNotToPerformAssertions();
        
        $command = new CartProcessCommand(cartId: '0198521d-7868-7d8f-9737-9789a7e1604d');
        
        $handler = static::getContainer()->get(CartProcessCommandHandler::class);
        $handler->__invoke($command);
    }
}