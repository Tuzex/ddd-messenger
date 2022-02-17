<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Messenger\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Ddd\Domain\DomainEvent;
use Tuzex\Ddd\Messenger\MessengerDomainEventBus;

final class MessengerDomainEventBusTest extends TestCase
{
    /**
     * @dataProvider provideDomainEvents
     */
    public function testItDispatchesDomainEventToMessageBus(array $domainEvents): void
    {
        $domainEventBus = new MessengerDomainEventBus(
            $this->mockMessageBus(count($domainEvents))
        );

        $domainEventBus->publish(...$domainEvents);
    }

    public function provideDomainEvents(): array
    {
        $domainEvent = $this->mockDomainEvent();

        return [
            'one' => [
                'domainEvents' => [$domainEvent],
            ],
            'multiple' => [
                'domainEvents' => [$domainEvent, $domainEvent],
            ],
        ];
    }

    private function mockDomainEvent(): DomainEvent
    {
        return $this->createMock(DomainEvent::class);
    }

    private function mockMessageBus(int $count): MessageBusInterface
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->expects($this->exactly($count))
            ->method('dispatch')
            ->willReturn(
                new Envelope($this->mockDomainEvent())
            );

        return $messageBus;
    }
}
