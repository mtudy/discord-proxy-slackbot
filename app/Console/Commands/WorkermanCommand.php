<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http;
use Workerman\Protocols\Http\Request as TcpRequest;
use Workerman\Worker;

class WorkermanCommand extends Command
{

    protected $signature = 'workman {action} {--d}';

    protected $description = 'Start a Workerman server.';

    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'workman';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start()
    {
        $this->startBusinessWorker();
        Worker::runAll();
    }

    private function startBusinessWorker(): void
    {
        $worker = new Worker('http://0.0.0.0:8000');
        $worker->count = 4;
        $worker->onMessage = function (TcpConnection $connection, TcpRequest $data) {
            $kernel = resolve(Kernel::class);
            $response = $kernel->handle(
                $request = Request::createFromBase(
                    new SymfonyRequest(
                        $data->get(),
                        $data->post(),
                        [],
                        (array)$data->cookie(),
                        $data->file(),
                        array_merge(
                            [
                                'SERVER_NAME' => $data->host(),
                                'SERVER_PORT' => $data->protocolVersion(),
                                'SERVER_PROTOCOL' => $data->protocolVersion(),
                                'REQUEST_TIME' => time(),
                                'REQUEST_METHOD' => $data->method(),
                                'REQUEST_URI' => $data->uri(),
                            ],
                            $data->header()
                        ),
                        $data->rawBody()
                    )
                )
            );

            $connection->__header = $response->headers->all();

            $connection->send($response->getContent() ?? '');

            $kernel->terminate($request, $response);

            gc_collect_cycles();
        };
    }
}
