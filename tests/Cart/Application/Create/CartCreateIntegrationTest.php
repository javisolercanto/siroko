<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application\Create;

use App\Cart\Application\Create\CartCreateCommand;
use App\Cart\Application\Create\CartCreateCommandHandler;
use App\Cart\Domain\Exception\CartNotFoundException;
use App\Tests\BaseTestCase;

class CartCreateIntegrationTest extends BaseTestCase
{
    public function testCartCreateIntegration(): void
    {
        $this->expectNotToPerformAssertions();
        
        $command = new CartCreateCommand(ownerId: '01985a76-b787-7c4c-ab8b-542aa31264eb');
        
        $handler = static::getContainer()->get(CartCreateCommandHandler::class);
        $handler->__invoke($command);
    }
}