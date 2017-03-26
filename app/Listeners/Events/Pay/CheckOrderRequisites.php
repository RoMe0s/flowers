<?php

namespace App\Listeners\Events\Pay;

use \Artem328\LaravelYandexKassa\Events\BeforeCheckOrderResponse;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckOrderRequisites
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
     * @param  BeforeCheckOrderResponse  $event
     * @return void
     */
    public function handle(BeforeCheckOrderResponse $event)
    {
        return null;
    }
}
