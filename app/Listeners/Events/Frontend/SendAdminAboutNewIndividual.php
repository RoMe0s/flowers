<?php

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\IndividualStored;
use App\Models\Group;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;
use Mail;

class SendAdminAboutNewIndividual implements ShouldQueue
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
     * @param  IndividualStored  $event
     * @return void
     */
    public function handle(IndividualStored $event)
    {
        $item = $event->individual;

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
                Mail::queue('emails.individual-item', ['item' => $item], function ($message) use ($user) {
                    $message->to($user->email);
                    $message->subject('Заказан индивидуальный товар');
                });
        }
    }
}
