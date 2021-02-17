<?php

declare(strict_types=1);

namespace App\Workerman;

class EventHandler
{
    public static function onWorkerStart($businessWorker)
    {
    }

    public static function onConnect($client_id)
    {
    }

    public static function onWebSocketConnect($client_id, $data)
    {
    }

    public static function onMessage($client_id, $message)
    {
        return $message;
    }

    public static function onClose($client_id)
    {
    }
}
