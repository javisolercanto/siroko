<?php

declare(strict_types=1);

namespace App\_Shared\Infrastructure\Persistence;

use ReflectionObject;

abstract class BaseOrm
{
    public function merge(self $target): void
    {
        $reflection = new ReflectionObject($target);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);

            $value = $property->getValue($target);
            $property->setValue($this, $value);
        }
    }
}
