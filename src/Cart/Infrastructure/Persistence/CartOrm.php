<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Persistence;

use App\_Shared\Infrastructure\Persistence\BaseOrm;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'cart')]
#[ORM\Index(columns: ['owner_id', 'processed'], name: 'cart_owner_id_processed_idx')]
final class CartOrm extends BaseOrm
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'guid')]
    private string $ownerId;

    #[ORM\Column(type: 'boolean')]
    private bool $processed;

    public function __construct(
        string $id,
        string $ownerId,
        bool $processed
    ) {
        $this->id = $id;
        $this->ownerId = $ownerId;
        $this->processed = $processed;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function ownerId(): string
    {
        return $this->ownerId;
    }

    public function processed(): bool
    {
        return $this->processed;
    }
}
