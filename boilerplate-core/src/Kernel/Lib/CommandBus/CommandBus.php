<?php

declare(strict_types=1);

namespace App\Kernel\Lib\CommandBus;

interface CommandBus
{
    /**
     * @param  object  $command
     */
    public function dispatch(object $command): void;
}
