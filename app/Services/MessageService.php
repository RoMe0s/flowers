<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use LetsAds;

class MessageService {

    private function _getPhone($phone) {

        preg_match('/\d+/', $phone);

        $phone[0] = '7';

        return $phone;

    }

    public function getBalance() {
    
        return LetsAds::balance(); 
    
    }

    public function registerSMS(User $user, $password) {

        $message = sprintf(variable("register_sms"), $password);

        $phone = $this->_getPhone($user->phone);
    
        $sending = LetsAds::send($message, config('letsads.name'), $phone);

        return $sending ? true: false;
    
    }
    
    public function passwordResetSMS(User $user, $code) {

        $message = sprintf(variable("reset_sms"), $code);

        $phone = $this->_getPhone($user->phone);

        $sending = LetsAds::send($message, config('letsads.name'), $phone);

        return $sending ? true: false;
        
    }

    public function orderConfirmedSMS(User $user, Order $order) {

        $message = sprintf(variable("order_confirmed_sms"), route('order.pay', ['id' => $order->id]));

        $phone = $order->prepay == 100 ? $user->phone : (!empty($order->recipient_phone) ? $order->recipient_phone : $user->phone);

        $phone = $this->_getPhone($phone);

        $sending = LetsAds::send($message, config('letsads.name'), $phone);

        return $sending ? true: false;

    }

    public function orderPaySuccessfulSMS(Order $order) {

        $message = variable("order_pay_successful_sms");

        $user = User::with(['info'])->where('id', $order->user_id)->first();

        $phone = $order->prepay == 100 ? $user->phone : (!empty($order->recipient_phone) ? $order->recipient_phone : $user->phone);

        if($phone) {

            $phone = $this->_getPhone($phone);

            $sending = LetsAds::send($message, config('letsads.name'), $phone);

            return $sending ? true: false;

        }

        return false;

    }

    public function orderStoredSMS(Order $order) {

        $message = sprintf(variable("order_stored_sms"), $order->id . '(' . $order->getTotal() . 'руб.)');

        $user = User::with(['info'])->where('id', $order->user_id)->first();

        $phone = $order->prepay == 100 ? $user->phone : (!empty($order->recipient_phone) ? $order->recipient_phone : $user->phone);

        if($phone) {

            $phone = $this->_getPhone($phone);

            $sending = LetsAds::send($message, config('letsads.name'), $phone);

            return $sending ? true: false;

        }

        return false;

    }

}
