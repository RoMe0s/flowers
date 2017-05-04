<?php

namespace App\Listeners\Events\Backend;

use App\Events\Backend\OrderStatusChanged;
use GuzzleHttp\Psr7\MessageTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Services\MessageService;

class OrderStatusChangedListener implements ShouldQueue
{

    use InteractsWithQueue;

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
     * @param  OrderStatusChanged  $event
     * @return void
     */
    public function handle(OrderStatusChanged $event)
    {
        $user = $event->order->user;

        $this->messageService->orderConfirmedSMS($user, $event->order);

        if($user && !empty($user->email)) {
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
