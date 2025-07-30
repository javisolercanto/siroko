<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Persistence;

use App\_Shared\Infrastructure\Persistence\BaseOrm;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'user')]
final class UserOrm extends BaseOrm
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid')]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string')]
    private string $email;

    public function __construct(
        string $id,
        string $name,
        string $email,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }
}
