<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\EditStock;

use App\_Shared\Domain\DomainEvents\OrderCreatedDomainEvent;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\_Shared\Message\Query\Domain\QueryBus;
use App\Product\Application\EditStock\ProductEditStockOnOrderCreated;
use App\Tests\BaseTestCase;

class ProductEditStockIntegrationTest extends BaseTestCase
{
    public function testProductEditStockIntegration(): void
    {
        $this->expectNotToPerformAssertions();

        $event = new OrderCreatedDomainEvent(
            orderId: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            cartId: '0198521d-7868-7d8f-9737-9789a7e1604d',
            ownerId: '123e4567-e89b-12d3-a456-426614174000',
        );

        $queryBus = $this->getContainer()->get(QueryBus::class);
        $commandBus = $this->getContainer()->get(CommandBus::class);

        $subscriber = new ProductEditStockOnOrderCreated(
            queryBus: $queryBus,
            commandBus: $commandBus,
        );

        $subscriber->__invoke($event);
    }
}
