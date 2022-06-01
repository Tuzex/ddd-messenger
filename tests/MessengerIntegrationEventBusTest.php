<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Messenger\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Ddd\Infrastructure\Integration\IntegrationEvent;
use Tuzex\Ddd\Messenger\MessengerIntegrationEventBus;

final class MessengerIntegrationEventBusTest extends TestCase
{
    /**
     * @dataProvider provideIntegrationEvents
     */
    public function testItDispatchesIntegrationEventToMessageBus(array $integrationEvents): void
    {
        $integrationEventBus = new MessengerIntegrationEventBus(
            $this->mockMessageBus(
                count($integrationEvents)
            )
        );

        $integrationEventBus->publish(...$integrationEvents);
    }

    public function provideIntegrationEvents(): array
    {
        $integrationEvent = $this->mockIntegrationEvent();

        return [
            'one' => [
                'integrationEvents' => [$integrationEvent],
            ],
            'multiple' => [
                'integrationEvents' => [$integrationEvent, $integrationEvent],
            ],
        ];
    }

    private function mockIntegrationEvent(): IntegrationEvent
    {
        return $this->createMock(IntegrationEvent::class);
    }

    private function mockMessageBus(int $count): MessageBusInterface
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->exactly($count))
            ->method('dispatch')
            ->willReturn(
                new Envelope($this->mockIntegrationEvent())
            );

        return $messageBus;
    }
}
