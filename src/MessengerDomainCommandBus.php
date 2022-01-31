<?php

declare(strict_types=1);

namespace Tuzex\Ddd\Messenger;

use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBusInterface;
use Tuzex\Ddd\Core\Application\DomainCommandBus;
use Tuzex\Ddd\Core\Domain\DomainCommand;
use Tuzex\Ddd\Messenger\Exception\NoHandlerForDomainCommandException;

final class MessengerDomainCommandBus implements DomainCommandBus
{
    public function __construct(
        private MessageBusInterface $messageBus
    ) {}

    public function dispatch(DomainCommand $domainCommand): void
    {
        try {
            $this->messageBus->dispatch($domainCommand);
        } catch (NoHandlerForMessageException $exception) {
            throw new NoHandlerForDomainCommandException($domainCommand, $exception);
        }
    }
}
