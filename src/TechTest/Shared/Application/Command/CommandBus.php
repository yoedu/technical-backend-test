<?php

declare(strict_types=1);

namespace App\TechTest\Shared\Application\Command;

interface CommandBus
{
    public function handle(Command $command): void;
}