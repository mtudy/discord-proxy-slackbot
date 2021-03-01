<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Notifications\DiscordNotification;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Container\LaravelContainer;
use BotMan\BotMan\Storages\Drivers\FileStorage;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Symfony\Component\HttpFoundation\Response;

class ListenBotManController extends Controller
{
    private FileStorage $fileStorage;

    private LaravelContainer $laravelContainer;

    private AnonymousNotifiable $anonymousNotifiable;

    private Repository $config;

    public function __construct(
        FileStorage $fileStorage,
        LaravelContainer $laravelContainer,
        AnonymousNotifiable $anonymousNotifiable,
        Repository $config
    ) {
        $this->fileStorage = $fileStorage;
        $this->laravelContainer = $laravelContainer;
        $this->anonymousNotifiable = $anonymousNotifiable;
        $this->config = $config;
    }

    public function __invoke(Request $request): Response
    {
        $challenge = $request->input('challenge');

        if ($challenge) {
            return response()->json(compact('challenge'));
        }

        $botMan = BotManFactory::create(
            $this->config->get('botman', []),
            new LaravelCache(),
            $request,
            $this->fileStorage
        );

        $botMan->setContainer($this->laravelContainer);

        $botMan->hears(
            '{message}',
            function (BotMan $botMan) use ($request) {
                $message = $botMan->getMessage()->getPayload();

                if (empty($message)) {
                    return;
                }

                $this->anonymousNotifiable->notify(
                    new DiscordNotification($message)
                );
            }
        );

        $botMan->listen();

        return response()->noContent();
    }
}
