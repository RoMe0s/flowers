<?php

namespace App\Listeners\Events\Backend;

use App\Events\Backend\OrderStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class OrderStatusChangedListener implements ShouldQueue
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
     * @param  OrderStatusChanged  $event
     * @return void
     */
    public function handle(OrderStatusChanged $event)
    {
        $user = $event->order->user;
        if($user) {
            Mail::queue(
                'emails.order_confirmed',
                ['order' => $event->order],
                function ($message) use ($user) {
                    $message->to($user->email, $user->getFullName())
                        ->subject('Ваш заказ подтверждён');
                }
            );
        }
    }
}
