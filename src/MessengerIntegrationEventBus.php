<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Messenger;

use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Ddd\Infrastructure\Integration\IntegrationEvent;
use Tuzex\Ddd\Infrastructure\Integration\IntegrationEventBus;

final class MessengerIntegrationEventBus implements IntegrationEventBus
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    ) {}

    public function publish(IntegrationEvent ...$integrationEvents): void
    {
        foreach ($integrationEvents as $integrationEvent) {
            $this->messageBus->dispatch($integrationEvent);
        }
    }
}
