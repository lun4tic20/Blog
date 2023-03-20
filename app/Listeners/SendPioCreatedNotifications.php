<?php

namespace App\Listeners;

use App\Events\PioCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Notifications\NewPio;
use App\Models\Pio;

class SendPioCreatedNotifications implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(public Pio $pio)
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PioCreated  $event
     * @return void
     */
    public function handle(PioCreated $event)
    {

        /*foreach(User::whereNot('id', $event->pio->user_id)->cursor() as $user) {
        $user->notify(new NewPio($event->pio));*/
    }
}
