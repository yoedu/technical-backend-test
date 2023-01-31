<?php

declare(strict_types=1);

namespace App\TechTest\Shared\Application\Query;

interface QueryBus
{
    public function query(Query $query): mixed;
}