<?php

declare(strict_types=1);

namespace App\TechTest\Registry\Application\Command;

use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\Item;
use App\TechTest\Registry\Domain\ItemRepository;
use App\TechTest\Registry\Domain\ItemValue;
use App\TechTest\Shared\Application\Command\CommandHandler;

class DeleteItemCommandHandler implements CommandHandler
{
    public function __construct(private ItemRepository $itemRepository)
    {
    }

    /**
     * @throws InvalidItemValueException
     * @throws ItemNotFoundException
     */
    public function __invoke(DeleteItemCommand $command): void
    {
        $itemValue = ItemValue::fromString($command->value());

        $this->itemRepository->remove(new Item($itemValue));
    }
}