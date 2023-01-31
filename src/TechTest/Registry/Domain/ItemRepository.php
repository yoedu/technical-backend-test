<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Domain;

interface ItemRepository
{
    public function get(ItemValue $itemValue): Item;
    public function save(Item $item): void;
    public function remove(Item $item): void;
}
