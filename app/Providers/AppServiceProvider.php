<?php

namespace App\Providers;

use App\Http\Controllers\ListenBotManController;
use BotMan\BotMan\Container\LaravelContainer;
use BotMan\BotMan\Storages\Drivers\FileStorage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\ServiceProvider;

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
            ->give(
                function () {
                    return (new AnonymousNotifiable())->route('slack', config('logging.channels.slack.url'));
                }
            );

        $this->app->when(ListenBotManController::class)
            ->needs(FileStorage::class)
            ->give(
                function () {
                    return new FileStorage(storage_path('botman'));
                }
            );

        $this->app->when(ListenBotManController::class)
            ->needs(LaravelContainer::class)
            ->give(
                function (Application $app) {
                    return new LaravelContainer($app);
                }
            );
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
