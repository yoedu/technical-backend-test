<?php

namespace App\TechTest\Registry\Domain\Exception;

class ItemNotFoundException extends \RuntimeException
{
    private string $messageTemplate= 'Item not found for value: %s';
    public function __construct($value)
    {
        parent::__construct(sprintf($this->messageTemplate, $value));
    }
}