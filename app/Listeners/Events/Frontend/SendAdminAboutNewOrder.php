<?php

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\FastOrderStored;
use App\Models\Group;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Mail;

class SendAdminAboutNewOrder implements ShouldQueue
{

    use InteractsWithQueue;

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
     * @param  FastOrderStored  $event
     * @return void
     */
    public function handle(FastOrderStored $event)
    {
        $item = $event->order;

        $groups = Group::all();
        $used_groups = array();
        foreach ($groups as $group) {
            if(in_array('administrator', array_keys($group->getPermissions())) || in_array('superuser', array_keys($group->getPermissions())) ) {
                $used_groups[] = $group->id;
            }
        }

        $users = User::select('users.email')
            ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
            ->whereIn('users_groups.group_id', $used_groups)
            ->get();

        foreach ($users as $user) {
            Mail::queue('emails.order', ['item' => $item], function ($message) use ($user) {
                $message->to($user->email);
                $message->subject('Новый заказ на сайте');
            });
        }
    }
}
