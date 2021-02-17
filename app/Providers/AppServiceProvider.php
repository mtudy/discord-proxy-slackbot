<?php

namespace App\Providers;

use App\Http\Controllers\ListenBotManController;
use BotMan\BotMan\BotMan;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ListenBotManController::class)
            ->needs(AnonymousNotifiable::class)
            ->give(function () {
                return (new AnonymousNotifiable())->route('slack', config('logging.channels.slack.url'));
            });

        $this->app->when(ListenBotManController::class)
            ->needs(BotMan::class)
            ->give(function () {
                return $this->app['botman'];
            });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
