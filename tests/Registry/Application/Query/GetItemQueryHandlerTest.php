<?php

namespace App\Tests\Registry\Application\Query;

use App\TechTest\Registry\Application\Query\GetItemQuery;
use App\TechTest\Registry\Application\Query\GetItemQueryHandler;
use App\TechTest\Registry\Application\Service\InvertedGetItemService;
use App\TechTest\Registry\Domain\Exception\InvalidItemValueException;
use App\TechTest\Registry\Domain\Exception\ItemNotFoundException;
use App\TechTest\Registry\Domain\Item;
use App\TechTest\Registry\Domain\ItemValue;
use App\TechTest\Registry\Infrastructure\InSessionInvertService;
use App\TechTest\Registry\Infrastructure\InSessionItemRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;

class GetItemQueryHandlerTest extends TestCase
{
    private const VALUE_IN_REGISTRY = 'blue';
    private const VALUE_NOT_IN_REGISTRY = 'green';
    private const VALUE_INVALID = 'g_reen';
    private const REGISTRY_VALUES = ['blue','red','yellow'];
    private const REGISTRY_KEY = 'items';
    private const INVERT_KEY = 'invert';
    private Session $session;
    private RequestStack $requestStack;
    private InSessionItemRepository $inSessionItemRepository;
    private InSessionInvertService $inSessionInvertService;
    private GetItemQueryHandler $getItemQueryHandler;
    private InvertedGetItemService $invertedGetItemService;

    public function testWhenValueInRegistryThenReturnIt(): void
    {
        $this->prepareMocks(false);
        $getItemQuery = new GetItemQuery(self::VALUE_IN_REGISTRY);
        $result = $this->getItemQueryHandler->__invoke($getItemQuery);

        $this->assertEquals(self::VALUE_IN_REGISTRY,$result);
    }

    public function testWhenValueNotInRegistryThenRaiseException(): void
    {
        $this->prepareMocks(false);
        $getItemQuery = new GetItemQuery(self::VALUE_NOT_IN_REGISTRY);

        $this->expectException(ItemNotFoundException::class);

        $this->getItemQueryHandler->__invoke($getItemQuery);
    }

    public function testWhenInvalidValueThenRaiseException(): void
    {
        $this->prepareMocks(false);
        $getItemQuery = new GetItemQuery(self::VALUE_INVALID);

        $this->expectException(InvalidItemValueException::class);

        $this->getItemQueryHandler->__invoke($getItemQuery);
    }

    public function testWhenInvertedAndValueInRegistryThenRaiseException(): void
    {
        $this->prepareMocks(true);
        $getItemQuery = new GetItemQuery(self::VALUE_IN_REGISTRY);

        $this->expectException(ItemNotFoundException::class);

        $this->getItemQueryHandler->__invoke($getItemQuery);
    }

    public function testWhenInvertedAndValueNotInRegistryThenReturnIt(): void
    {
        $this->prepareMocks(true);
        $getItemQuery = new GetItemQuery(self::VALUE_NOT_IN_REGISTRY);
        $result = $this->getItemQueryHandler->__invoke($getItemQuery);

        $this->assertEquals(self::VALUE_NOT_IN_REGISTRY,$result);
    }

    private function prepareMocks(bool $inverted): void
    {
        $items = $this->prepareItems(self::REGISTRY_VALUES);
        $this->session = $this->createMock(Session::class);
        $this->session->method('get')
            ->withConsecutive([self::INVERT_KEY],[self::REGISTRY_KEY,[]])
            ->willReturnOnConsecutiveCalls($inverted, $items);
        $this->requestStack = $this->createMock(RequestStack::class);
        $this->requestStack->method('getSession')->willReturn($this->session);
        $this->inSessionItemRepository = new InSessionItemRepository($this->requestStack);
        $this->inSessionInvertService = new InSessionInvertService($this->requestStack);
        $this->invertedGetItemService = new InvertedGetItemService($this->inSessionItemRepository);
        $this->getItemQueryHandler = new GetItemQueryHandler(
            $this->inSessionItemRepository,
            $this->inSessionInvertService,
            $this->invertedGetItemService
        );
    }
    private function prepareItems(array $values): array
    {
        return array_map(
            static function ($value) {
                return new Item(ItemValue::fromString($value));
            },
            $values
        );
    }
}
