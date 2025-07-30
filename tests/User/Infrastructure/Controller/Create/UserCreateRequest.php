<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Controller\Create;

use Symfony\Component\Validator\Constraints as Assert;

class UserCreateRequest
{
    #[Assert\NotBlank()]
    public string $name;

    #[Assert\NotBlank()]
    public string $email;
}
