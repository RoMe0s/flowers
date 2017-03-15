<?php

namespace App\Listeners\Events\Pay;

use App\Models\Order;
use App\Models\User;
use \Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class ChangeOrderStatusWhenPaymentSuccessful implements ShouldQueue
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
     * @param  BeforePaymentAvisoResponse  $event
     * @return void
     */
    public function handle(BeforePaymentAvisoResponse $event)
    {
        if ($event->request->isValidHash()) {
            $order = Order::with('items')->find($event->request->get('orderNumber'));

            if ($order->desc == 'Оплата подписки') {
                $order->status = 5;
                $order->save();

                $user = User::find($order->user_id);

                $oi = $order->items()->first();

                if($oi) {

                    $subscription = $user->subscriptions()->updateExistingPivot($oi->itemable_id, Carbon::now()->addDays(31)->toDateString())->first();
                    $subscription->save();

                }

            } else {
                $order->status = 3;
                $order->save();
            }

            $groups = Group::all();
            $used_groups = array();
            foreach ($groups as $group) {
                if(in_array('administrator', $group->getPermissions()) || in_array('superuser', $group->getPermissions())) {
                    $used_groups[] = $group->id;
                }
            }

            $users = User::select('users.email')
                ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->whereIn('users_groups.group_id', $used_groups)
                ->get();

            foreach ($users as $user) {
                Mail::queue('emails.payment', ['order' => $order], function ($message) use ($user, $order) {
                    $message->to($user->email);
                    $message->subject('Заказ #'.$order->id.' оплачен');
                });
            }

        }
    }
}
