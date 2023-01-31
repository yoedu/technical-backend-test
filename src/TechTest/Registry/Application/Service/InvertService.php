<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Service;

interface InvertService
{
    public function inverted(): bool;
    public function toggle(): void;
}
