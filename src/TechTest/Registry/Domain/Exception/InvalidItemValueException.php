<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Domain\Exception;

class InvalidItemValueException extends \RuntimeException
{
    private string $messageTemplate= 'Invalid item value: %s (Only alphanumeric and spaces are allowed)';
    public function __construct($value)
    {
        parent::__construct(sprintf($this->messageTemplate, $value));
    }
}