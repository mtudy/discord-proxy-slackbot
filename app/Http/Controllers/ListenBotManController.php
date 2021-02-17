<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Notifications\DiscordNotification;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Symfony\Component\HttpFoundation\Response;

class ListenBotManController extends Controller
{
    private BotMan $botMan;

    private AnonymousNotifiable $anonymousNotifiable;

    public function __construct(BotMan $botMan, AnonymousNotifiable $anonymousNotifiable)
    {
        $this->botMan = $botMan;
        $this->anonymousNotifiable = $anonymousNotifiable;
    }

    public function __invoke(Request $request): Response
    {
        $challenge = $request->input('challenge');

        if ($challenge) {
            return response()->json(compact('challenge'));
        }

        $this->botMan->hears(
            '{message}',
            function (BotMan $botMan) {
                [ , $message] = func_get_args();

                $this->anonymousNotifiable->notify(new DiscordNotification($message));
            }
        );

        $this->botMan->listen();

        return response()->noContent();
    }
}
