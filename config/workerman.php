<?php

return [
    'host' => env('WORKERMAN_HTTP_HOST', '0.0.0.0'),
    'port' => env('WORKERMAN_HTTP_PORT', '8000'),
    'worker_count' => env('WORKERMAN_WORKER_COUNT', 2),
];
