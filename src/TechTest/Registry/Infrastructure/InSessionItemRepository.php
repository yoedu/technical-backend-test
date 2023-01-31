<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Infrastructure;

use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\Exception\ItemValueAlreadyExistException;
use App\TechTest\Registry\Domain\Item;
use App\TechTest\Registry\Domain\ItemRepository;
use App\TechTest\Registry\Domain\ItemValue;
use Symfony\Component\HttpFoundation\RequestStack;

class InSessionItemRepository implements ItemRepository
{
    private const SESSION_KEY = 'items';

    public function __construct(private RequestStack $requestStack)
    {
    }

    public function save(Item $item): void
    {
        $filteredRegistries = $this->searchByValue($item->itemValue());

        if (count($filteredRegistries) !== 0) {
            throw new ItemValueAlreadyExistException($item->itemValue()->value());
        }

        $items = $this->getSessionRegistries();

        $items[] = $item;

        $this->saveSessionRegistries($items);
    }

    public function remove(Item $item): void
    {
        $filteredRegistries = $this->searchByValue($item->itemValue());
        if (count($filteredRegistries) === 0) {
            throw new ItemNotFoundException($item->itemValue()->value());
        }

        $indexes = array_keys($filteredRegistries);

        $items = $this->getSessionRegistries();

        unset($items[reset($indexes)]);

        $this->saveSessionRegistries($items);
    }

    public function get(ItemValue $itemValue): Item
    {
        $filteredRegistries = $this->searchByValue($itemValue);

        if (count($filteredRegistries) === 0) {
            throw new ItemNotFoundException($itemValue->value());
        }

        return reset($filteredRegistries);
    }

    private function searchByValue(ItemValue $itemValue): array
    {
        return array_filter($this->getSessionRegistries(),
            static function ($item) use ($itemValue) {
                return $item->itemValue()->isEqualTo($itemValue);
            });
    }

    private function getSessionRegistries(): array
    {
        return $this->requestStack->getSession()->get(self::SESSION_KEY, []);
    }

    private function saveSessionRegistries(array $items): void
    {
        $this->requestStack->getSession()->set(self::SESSION_KEY, $items);
    }
}