<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Domain\Exception;

class ItemValueAlreadyExistException extends \RuntimeException
{
    private string $messageTemplate= 'Can\'t add item with value: %s as already exist';
    public function __construct($value)
    {
        parent::__construct(sprintf($this->messageTemplate, $value));
    }
}