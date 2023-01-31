<?php

declare(strict_types=1);

namespace App\TechTest\Shared\Infrastructure\Bus\Command;

use App\TechTest\Shared\Application\Command\Command;
use App\TechTest\Shared\Application\Command\CommandBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function handle(Command $command) :void
    {
        try {
            $this->messageBus->dispatch($command);
        } catch (HandlerFailedException $e) {
            throw $e->getPrevious();
        }
    }
}