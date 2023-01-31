<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Command;

use App\TechTest\Shared\Application\Command\Command;

class AddItemCommand implements Command
{
    public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }
}