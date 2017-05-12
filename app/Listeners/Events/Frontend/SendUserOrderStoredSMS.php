<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\FastOrderStored;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\MessageService;
use Mail;

/**
 * Class SendUserActivationEmail
 * @package App\Listeners\Events\Frontend
 */
class SendUserOrderStoredSMS implements ShouldQueue
{
    use InteractsWithQueue;

    protected $messageService;

    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\FrontEnd\SendUserOrderStoredSMS
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\FrontEnd\FastOrderStored $event
     */
    public function handle(FastOrderStored $event)
    {
        $order = $event->order;

        $this->messageService->orderStoredSMS($order);

    }
}