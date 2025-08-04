<?php

declare(strict_types=1);

namespace App\_Shared\Infrastructure\Persistence;

use ReflectionObject;

abstract class BaseOrm
{
    /**
     * Overwrites the properties of the object while maintaining the ORM reference of the original object
     * 
     * @param self $target The orm object to get values
     */
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
