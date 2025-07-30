<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Persistence;

use App\_Shared\Infrastructure\Persistence\BaseOrm;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'cart_line')]
#[ORM\Index(columns: ['cart_id'], name: 'cart_id_idx')]
#[ORM\Index(columns: ['cart_id', 'product_id'], name: 'cart_id_product_id_idx')]
final class CartLineOrm extends BaseOrm
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'guid')]
    private string $ownerId;

    #[ORM\Column(type: 'guid')]
    private string $cartId;

    #[ORM\Column(type: 'guid')]
    private string $productId;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    public function __construct(
        string $id,
        string $ownerId,
        string $cartId,
        string $productId,
        int $quantity,
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function ownerId(): string
    {
        return $this->ownerId;
    }

    public function cartId(): string
    {
        return $this->cartId;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
