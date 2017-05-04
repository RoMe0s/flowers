<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 04.11.15
 * Time: 16:36
 */

namespace App\Listeners\Events\Frontend;

use App\Events\Frontend\UserRegister;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\MessageService;
use Mail;

/**
 * Class SendUserActivationEmail
 * @package App\Listeners\Events\Frontend
 */
class SendUserActivationEmail implements ShouldQueue
{

    use InteractsWithQueue;

    protected $messageService;

    /**
     * Create the event handler.
     *
     * @return \App\Listeners\Events\FrontEnd\SendUserActivationEmail
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\FrontEnd\UserRegister $event
     */
    public function handle(UserRegister $event)
    {

        $user = $event->user;

        $this->messageService->registerSMS($user, $event->input['password']);

        if(isset($user->email) && !empty($user->email)) {

            Mail::queue(
                'emails.reg',
                ['login' => $user->login, 'password' => $event->input['password']],
                function ($message) use ($user) {
                    $message->to($user->email, $user->getFullName())
                        ->subject('Регистрация на ' . config('app.name'));
                }
            );

        }
    }
}