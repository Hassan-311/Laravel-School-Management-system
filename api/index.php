<?php

define('LARAVEL_START', microtime(true));

$_ENV['APP_BASE_PATH'] = dirname(__DIR__);

require dirname(__DIR__) . '/vendor/autoload.php';

$app = (require_once dirname(__DIR__) . '/bootstrap/app.php');

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = tap($kernel->handle(
    $request = Illuminate\Http\Request::capture()
))->send();

$kernel->terminate($request, $response);