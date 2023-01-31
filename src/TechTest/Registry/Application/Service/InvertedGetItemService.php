<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Service;

use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\ItemRepository;
use App\TechTest\Registry\Domain\ItemValue;

final class InvertedGetItemService
{
    public function __construct(private ItemRepository $itemRepository)
    {}

    public function invertResult(ItemValue $itemValue): string
    {
        try {
            $this->itemRepository->get($itemValue);
        }
        catch (ItemNotFoundException $e) {
            return $itemValue->value();
        }
        throw new ItemNotFoundException($itemValue->value());
    }
}