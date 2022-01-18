<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\User;
use Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MailNotify;

class NotifyProductCreated implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $users = User::all();

        foreach($users as $user) {
           Mail::to($user->email)->send(new MailNotify($event->product));
           Log::info('Mail sent to '. $user->name);
        }
    }
}
