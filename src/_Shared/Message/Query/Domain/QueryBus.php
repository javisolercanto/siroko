<?php

declare(strict_types=1);

namespace App\_Shared\Message\Query\Domain;

interface QueryBus
{
    public function ask(Query $query): mixed;
}
