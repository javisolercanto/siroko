<?php

declare(strict_types=1);

namespace App\CartLine\Infrastructure\Controller\Create;

use Symfony\Component\Validator\Constraints as Assert;

class CartLineCreateRequest
{
    #[Assert\NotBlank()]
    public string $cart_id;

    #[Assert\NotBlank()]
    public string $product_id;

    #[Assert\NotBlank()]
    public int $quantity;
}
