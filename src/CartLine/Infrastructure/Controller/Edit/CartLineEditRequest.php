<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Controller\Edit;

use Symfony\Component\Validator\Constraints as Assert;

class CartLineEditRequest
{
    #[Assert\NotBlank()]
    public int $quantity;
}
