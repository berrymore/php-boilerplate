<?php

declare(strict_types=1);

namespace App\Kernel\Lib\CommandBus;

use Component\Bus\Bus;
use Component\Bus\Stamps\BusNameStamp;

final readonly class CommandBusImpl implements CommandBus
{
    public function __construct(private Bus $bus)
    {
    }

    public function dispatch(object $command): void
    {
        $this->bus->dispatch($command, [new BusNameStamp('command_bus')]);
    }
}
