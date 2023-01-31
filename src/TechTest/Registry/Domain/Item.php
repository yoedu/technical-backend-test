<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Domain;

class Item
{
    public function __construct(private ItemValue $itemValue)
    {
    }
    public function itemValue() : ItemValue
    {
        return $this->itemValue;
    }
}