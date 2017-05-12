<?php

namespace App\Listeners\Events\Pay;

use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Group;
use \Artem328\LaravelYandexKassa\Events\BeforePaymentAvisoResponse;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Services\MessageService;

class ChangeOrderStatusWhenPaymentSuccessful
{

    protected $messageService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
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

                if($oi->itemable instanceof Subscription) {

                    \DB::table('users_subscriptions')->where('user_id', $user->id)->where('subscription_id', $oi->itemable_id)->update([
                        'paid_before' => Carbon::now()->addDays(31)->toDateString()
                    ]);

                }

            } else {
                $order->status = 3;
                $order->save();
            }

            $groups = Group::all();
            $used_groups = array();
            foreach ($groups as $group) {
                if(in_array('administrator', array_keys($group->getPermissions())) || in_array('superuser', array_keys($group->getPermissions())) ) {
                    $used_groups[] = $group->id;
                }
            }

            //send sms

            $this->messageService->orderPaySuccessfulSMS($order);

            //end send sms

            $users = User::select('users.email')
                ->leftJoin('users_groups', 'users.id', '=', 'users_groups.user_id')
                ->whereIn('users_groups.group_id', $used_groups)
                ->get();

            foreach ($users as $user) {
                if(!empty($user->email)) {
                    Mail::queue('emails.payment', ['order' => $order], function ($message) use ($user, $order) {
                        $message->to($user->email);
                        $message->subject('Заказ #' . $order->id . ' оплачен');
                    });
                }
            }

        }
    }
}
