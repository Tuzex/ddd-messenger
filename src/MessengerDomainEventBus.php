<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Ddd\Application\DomainEventBus;
use Tuzex\Ddd\Domain\DomainEvent;

final class MessengerDomainEventBus implements DomainEventBus
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {}

    public function publish(DomainEvent ...$domainEvents): void
    {
        foreach ($domainEvents as $domainEvent) {
            $this->messageBus->dispatch($domainEvent);
        }
    }
}
