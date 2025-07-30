<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Persistence;

use App\_Shared\Infrastructure\Persistence\BaseOrm;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'cart_order')]
#[ORM\Index(columns: ['owner_id'], name: 'cart_order_owner_id_idx')]
final class OrderOrm extends BaseOrm
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'guid')]
    private string $ownerId;

    #[ORM\Column(type: 'guid')]
    private string $cartId;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    public function __construct(
        string $id,
        string $ownerId,
        string $cartId,
        \DateTimeInterface $date
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->cartId = $cartId;
        $this->date = $date;
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

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }
}
