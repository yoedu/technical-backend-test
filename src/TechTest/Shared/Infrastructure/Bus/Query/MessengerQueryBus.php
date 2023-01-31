<?php

declare(strict_types=1);

namespace App\TechTest\Shared\Infrastructure\Bus\Query;

use App\TechTest\Shared\Application\Query\QueryBus;
use App\TechTest\Shared\Application\Query\Query;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class MessengerQueryBus implements QueryBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function query(Query $query) :mixed
    {
        try {
            $envelope = $this->messageBus->dispatch($query);

            /** @var HandledStamp $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            return $stamp->getResult();
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}