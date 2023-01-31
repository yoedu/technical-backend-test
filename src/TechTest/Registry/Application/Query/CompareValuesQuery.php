<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Query;

use App\TechTest\Shared\Application\Query\Query;

final class CompareValuesQuery implements Query
{
    public function __construct(private array $values)
    {
    }

    public function values(): array
    {
        return $this->values;
    }
}
