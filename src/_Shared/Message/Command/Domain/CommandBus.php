<?php

declare(strict_types=1);

namespace App\_Shared\Message\Command\Domain;

interface CommandBus
{
    public function dispatch(Command $command): void;
}
