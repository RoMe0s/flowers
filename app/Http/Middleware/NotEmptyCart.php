<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 12.03.17
 * Time: 23:29
 */

namespace App\Http\Middleware;

use Closure;
use Kingpabel\Shoppingcart\Facades\Cart;

class NotEmptyCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cart::count() == 0) return redirect()->route('cart');

        return $next($request);
    }
}