<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Query;

use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\ItemRepository;
use App\TechTest\Registry\Domain\ItemValue;
use App\TechTest\Shared\Application\Query\QueryHandler;

final class CompareValuesQueryHandler implements QueryHandler
{
    public function __construct(private ItemRepository $itemRepository)
    {
    }

    /**
     * @throws InvalidItemValueException
     * @throws ItemNotFoundException
     */
    public function __invoke(CompareValuesQuery $query): array
    {
        $itemValues = array_map(
            static function ($value) {
                return ItemValue::fromString($value);
            },
            $query->values()
        );

        $diffValues = [];
        foreach($itemValues as $itemValue) {
            try {
                $this->itemRepository->get($itemValue);
            }
            catch (ItemNotFoundException $e) {
                $diffValues[] = $itemValue;
            }
        }

        return array_map(
            static function ($itemValue) {
                return $itemValue->value();
            },
            $diffValues
        );
    }
}
