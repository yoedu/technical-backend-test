<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Query;

use App\TechTest\Registry\Application\Service\InvertedGetItemService;
use App\TechTest\Registry\Application\Service\InvertService;
use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\ItemRepository;
use App\TechTest\Registry\Domain\ItemValue;
use App\TechTest\Shared\Application\Query\QueryHandler;

final class GetItemQueryHandler implements QueryHandler
{
    public function __construct(
        private ItemRepository $itemRepository,
        private InvertService  $invertService,
        private InvertedGetItemService $invertedGetItemService
    )
    {}

    /**
     * @throws InvalidItemValueException
     * @throws ItemNotFoundException
     */
    public function __invoke(GetItemQuery $query): string
    {
        $itemValue = ItemValue::fromString($query->value());

        if ($this->invertService->inverted()) {
            return $this->invertedGetItemService->invertResult($itemValue);
        }

        $item = $this->itemRepository->get($itemValue);

        return  $item->itemValue()->value();
    }
}
