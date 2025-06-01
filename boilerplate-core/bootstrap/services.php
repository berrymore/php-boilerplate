<?php

declare(strict_types=1);

return [
    \App\Kernel\Providers\ConfigProvider::class,
    \App\Kernel\Providers\BusProvider::class,
    \App\Kernel\Providers\HttpProvider::class,
    \App\Kernel\Providers\ORMProvider::class,
    \App\Kernel\Providers\ValidatorProvider::class,
    //
    \App\Welcome\WelcomeProvider::class,
];
