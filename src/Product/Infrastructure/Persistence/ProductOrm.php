<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Persistence;

use App\_Shared\Infrastructure\Persistence\BaseOrm;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'product')]
final class ProductOrm extends BaseOrm
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'float')]
    private float $price;

    #[ORM\Column(type: 'integer')]
    private int $stock;

    #[ORM\Column(type: 'integer')]
    private int $availableStock;

    public function __construct(
        string $id,
        string $name,
        float $price,
        int $stock,
        int $availableStock,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
        $this->availableStock = $availableStock;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function stock(): int
    {
        return $this->stock;
    }

    public function availableStock(): int
    {
        return $this->availableStock;
    }
}
