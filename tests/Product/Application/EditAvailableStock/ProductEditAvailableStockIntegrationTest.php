<?php

declare(strict_types=1);

namespace App\Tests\Product\Application\EditAvailableStock;

use App\_Shared\Domain\DomainEvents\CartLineCreatedDomainEvent;
use App\_Shared\Domain\DomainEvents\CartLineUpdatedDomainEvent;
use App\_Shared\Message\Command\Domain\CommandBus;
use App\CartLine\Domain\Entity\CartLineId;
use App\Product\Application\EditAvailableStock\ProductEditAvailableStockOnCartLineCreated;
use App\Product\Application\EditAvailableStock\ProductEditAvailableStockOnCartLineUpdated;
use App\Tests\BaseTestCase;

class ProductEditAvailableStockIntegrationTest extends BaseTestCase
{
    public function testProductEditAvailableStockIntegrationOnCartLineCreated(): void
    {
        $this->expectNotToPerformAssertions();

        $event = new CartLineCreatedDomainEvent(
            cartLineId: CartLineId::generate()->value(),
            product_id: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            quantity: 1,
        );

        $commandBus = $this->getContainer()->get(CommandBus::class);

        $subscriber = new ProductEditAvailableStockOnCartLineCreated(
            commandBus: $commandBus,
        );

        $subscriber->__invoke($event);
    }

    public function testProductEditAvailableStockIntegrationOnCartLineUpdatedMore(): void
    {
        $this->expectNotToPerformAssertions();

        $event = new CartLineUpdatedDomainEvent(
            cartLineId: CartLineId::generate()->value(),
            productId: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            oldQuantity: 1,
            newQuantity: 2,
        );

        $commandBus = $this->getContainer()->get(CommandBus::class);

        $subscriber = new ProductEditAvailableStockOnCartLineUpdated(
            commandBus: $commandBus,
        );

        $subscriber->__invoke($event);
    }

    public function testProductEditAvailableStockIntegrationOnCartLineUpdatedLess(): void
    {
        $this->expectNotToPerformAssertions();

        $event = new CartLineUpdatedDomainEvent(
            cartLineId: CartLineId::generate()->value(),
            productId: '019850bc-0cc9-74f3-886f-ff13031f2fc8',
            oldQuantity: 2,
            newQuantity: 1,
        );

        $commandBus = $this->getContainer()->get(CommandBus::class);

        $subscriber = new ProductEditAvailableStockOnCartLineUpdated(
            commandBus: $commandBus,
        );

        $subscriber->__invoke($event);
    }
}
