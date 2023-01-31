<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Query;

use App\TechTest\Shared\Application\Query\Query;

final class GetItemQuery implements Query
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}
