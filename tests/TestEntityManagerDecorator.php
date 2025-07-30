<?php

declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManagerInterface;

class TestEntityManagerDecorator extends EntityManagerDecorator
{
    public function flush(): void
    {
        // do nothing
    }
}
