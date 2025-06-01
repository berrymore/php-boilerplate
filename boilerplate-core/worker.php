<?php

require_once __DIR__ . '/bootstrap/bootstrap.php';

use App\Kernel\Http\Kernel;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

$container = boot();
$kernel    = $container->get(Kernel::class);
$worker    = Worker::create();

$factory     = new Psr17Factory();
$psrFactory  = new PsrHttpFactory($factory, $factory, $factory, $factory);
$httpFactory = new HttpFoundationFactory();
$psr7        = new PSR7Worker($worker, $factory, $factory, $factory);

while (true) {
    try {
        $request = $psr7->waitRequest();
        if ($request === null) {
            break;
        }
    } catch (Throwable $e) {
        $psr7->respond(new Response(400));
        continue;
    }

    try {
        $psr7->respond($psrFactory->createResponse($kernel->handle($httpFactory->createRequest($request))));
    } catch (Throwable $e) {
        $psr7->respond(new Response(500, [], $e->getMessage()));
    }
}
