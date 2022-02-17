<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Messenger\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Ddd\Domain\DomainCommand;
use Tuzex\Ddd\Messenger\Exception\NoHandlerForDomainCommandException;
use Tuzex\Ddd\Messenger\MessengerDomainCommandBus;

final class MessengerDomainCommandBusTest extends TestCase
{
    /**
     * @dataProvider provideDomainCommands
     */
    public function testItDispatchesDomainCommandToMessageBus(array $domainCommands): void
    {
        $domainCommandBus = new MessengerDomainCommandBus(
            $this->mockMessageBus(count($domainCommands))
        );

        $domainCommandBus->dispatch(...$domainCommands);
    }

    /**
     * @dataProvider provideDomainCommands
     */
    public function testItThrowsExceptionIfDomainCommandHandlerNotExists(array $domainCommands): void
    {
        $domainCommandBus = new MessengerDomainCommandBus(
            $this->mockMessageBus(1, false)
        );

        $this->expectException(NoHandlerForDomainCommandException::class);
        $domainCommandBus->dispatch(...$domainCommands);
    }

    public function provideDomainCommands(): array
    {
        $domainCommand = $this->mockDomainCommand();

        return [
            'one' => [
                'domainCommands' => [$domainCommand],
            ],
            'multiple' => [
                'domainCommands' => [$domainCommand, $domainCommand],
            ],
        ];
    }

    private function mockDomainCommand(): DomainCommand
    {
        return $this->createMock(DomainCommand::class);
    }

    private function mockMessageBus(int $count = 1, bool $handle = true): MessageBusInterface
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $dispatchMethod = $messageBus->expects($this->exactly($count))
            ->method('dispatch')
            ->willReturn(
                new Envelope($this->mockDomainCommand())
            );

        if (! $handle) {
            $dispatchMethod->willThrowException(new NoHandlerForMessageException());
        }

        return $messageBus;
    }
}
