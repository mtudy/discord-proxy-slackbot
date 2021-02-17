<?php

namespace Tests\Feature;

use App\Http\Controllers\ListenBotManController;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->app->when(ListenBotManController::class)
            ->needs(AnonymousNotifiable::class)
            ->give(function () {
                $mock = $this->createMock(AnonymousNotifiable::class);
                $mock->expects($this->once())
                    ->method('notify');

                return $mock;
            });

        $response = $this->postJson('/', json_decode('{
    "token": "one-long-verification-token",
    "team_id": "T061EG9R6",
    "api_app_id": "A0PNCHHK2",
    "event": {
        "type": "message",
        "channel": "C024BE91L",
        "user": "U2147483697",
        "text": "Live long and prospect.",
        "ts": "1355517523.000005",
        "event_ts": "1355517523.000005",
        "channel_type": "channel"
    },
    "type": "event_callback",
    "authed_teams": [
        "T061EG9R6"
    ],
    "event_id": "Ev0PV52K21",
    "event_time": 1355517523
}', true));

        $response->assertNoContent();
    }
}
