<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MailNotify;

class NotifyPostCreated implements ShouldQueue
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
     * @param  \App\Events\PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $users = DB::table('users')
            ->select('users.email', 'users.name')
            ->join('role_users', 'role_users.user_id', '=', 'users.id')
            ->get();

        //Log::info('user list: ', $users);

        foreach($users as $user) {
           Mail::to($user->email)->send(new MailNotify($event->post));
           Log::info('Mail sent to '. $user->name);
        }
    }
}
