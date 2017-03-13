<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;

use Kingpabel\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;
use App\Models\Set;
use App\Models\Bouquet;
use App\Models\Product;
use App\Models\Sale;

class CartController extends FrontendController
{


    /**
     * Return view with cart collection's items
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        if (sizeof(Cart::count())) {
            foreach (Cart::content() as $row) {
                if ($row->options['category'] != 'sales')
                    Cart::update($row->rowid, ['discount' => CartController::_itemDiscount($row->price)]);
            }
        }

        return view('cart');
    }

    /**
     * Add item to cart collection
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addSet(Request $request) {
        $data = $request->all();

        $val = Validator::make($data, [
            'id' => 'required|integer|exists:sets,id'
        ]);

        if ($val->fails()) {
            if ($request->ajax()) return response()->json('Ошибка добавления в корзину', 400);
            else return redirect()->back()->withErrors('Ошибка добавления в корзину');
        }

        $set = Set::find($data['id']);

        if (!$set->hasInStock()) {
            if ($request->ajax()) return response()->json('Товара нет в наличии', 404);
            else return redirect()->back()->withErrors('Товара нет в наличии');
        }

        $box = $set->box;

        if (!$request->ajax()) $this->_cartDestroy();

        Cart::add([
            'id' => $set->id,
            'name' => $box->category->name.', '.$box->title.' ('.$box->size().')',
            'qty' => 1,
            'price' => $set->price,
            'discount' => static::_itemDiscount($set->price),
            'options' => [
                'category' => 'sets',
                'image' => $set->image
            ]
        ]);

        if ($request->ajax())
            return response()->json([
                                        'msg' => 'Добавленно в корзину',
                                        'price' => Cart::total(),
                                        'count' => Cart::count()
                                    ], 200);
        else return redirect('/cart/make/fast/order');

    }

    /**
     * Add item to cart collection
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addBouquet(Request $request) {
        $data = $request->all();

        $val = Validator::make($data, [
            'id' => 'required|integer|exists:bouquets,id'
        ]);

        if ($val->fails()) {
            if ($request->ajax()) return response()->json('Ошибка добавления в корзину', 400);
            else return redirect()->back()->withErrors('Ошибка добавления в корзину');
        }

        $bouquet = Bouquet::find($data['id']);

        if (!$bouquet->hasInStock()) {
            if ($request->ajax()) return response()->json('Товара нет в наличии', 404);
            else return redirect()->back()->withErrors('Товара нет в наличии');
        }

        if (!$request->ajax()) $this->_cartDestroy();

        Cart::add([
            'id' => $bouquet->id,
            'name' => $bouquet->name,
            'qty' => 1,
            'price' => $bouquet->price,
            'discount' => static::_itemDiscount($bouquet->price),
            'options' => [
                'category' => 'bouquets',
                'image' => $bouquet->image
            ]
        ]);

        if ($request->ajax()) return response()->json([
            'msg' => 'Добавленно в корзину',
            'price' => Cart::total(),
            'count' => Cart::count()
        ], 200);
        else return redirect('/cart/make/fast/order');
    }

    /**
     * Add item to cart collection
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addSale(Request $request) {
        $data = $request->all();

        $val = Validator::make($data, [
            'id' => 'required|integer|exists:sales,id'
        ]);

        if ($val->fails()) {
            if ($request->ajax()) return response()->json('Ошибка добавления в корзину', 400);
            else return redirect()->back()->withErrors('Ошибка добавления в корзину');
        }

        $sale = Sale::get($data['id']);

        if (!$request->ajax()) $this->_cartDestroy();

        Cart::add([
            'id' => $sale->id,
            'name' => $sale->name,
            'qty' => 1,
            'price' => $sale->price,
            'discount' => static::_itemDiscount($sale->price),
            'options' => [
                'category' => 'sales',
                'image' => $sale->image
            ]
        ]);

        if ($request->ajax()) return response()->json([
            'msg' => 'Добавленно в корзину',
            'price' => Cart::total(),
            'count' => Cart::count()
        ], 200);
        else return redirect('/cart/make/fast/order');
    }

    /**
     * Add item to cart collection
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function addProduct(Request $request) {
        $data = $request->all();

        $val = Validator::make($data, [
            'id' => 'required|integer|exists:related_products,id'
        ]);

        if ($val->fails()) {
            if ($request->ajax()) return response()->json('Ошибка добавления в корзину', 400);
            else return redirect()->back()->withErrors('Ошибка добавления в корзину');
        }

        $product = Product::find($data['id']);

        if (!$request->ajax()) static::_cartDestroy();

        $size = !empty($product->size)? ' ('.$product->size.')': '';

        Cart::add([
            'id' => $product->id,
            'name' => $product->name.$size,
            'qty' => 1,
            'price' => $product->price,
            'discount' => static::_itemDiscount($product->price),
            'options' => [
                'category' => 'items',
                'image' => $product->image
            ]
        ]);

        if ($request->ajax()) return response()->json([
            'msg' => 'Добавленно в корзину',
            'price' => Cart::total(),
            'count' => Cart::count()
        ], 200);
        else return redirect('/cart/make/fast/order');
    }

    /**
     * Remove item from cart collection
     *
     * @param $item
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeItem($item, $id) {
        Cart::remove($id);

        return redirect()->back();
    }

    /**
     * Increment item's qty
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function qtyPlus($id) {
        $item = Cart::get($id);

        Cart::update($id, $item->qty + 1);

        return redirect()->back();
    }

    /**
     * Decrement item's qty
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function qtyMinus($id) {
        $item = Cart::get($id);

        if ($item->qty == 1) return redirect()->back();

        Cart::update($id, $item->qty - 1);

        return redirect()->back();
    }

    /**
     * Apply code to current cart collection
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function applyCode(Request $request) {
        $data = $request->all();

        $val = validator($data, [
            'code' => 'required|string|between:3,10',
        ]);

        if ($val->fails()) return redirect()->back()->withErrors($val);

        $code = Code::getByName($data['code']);

        if (is_null($code)) return redirect()->back()->withErrors('Такого промо-кода не существует');

        if (strtotime($code->date) < strtotime(date("Y-m-d")))
            return redirect()->back()->withErrors('Срок действия промокода истёк');

        if (Auth::user()->hasCode($code) || $request->session()->has('cart_discount_code'))
            return redirect()->back()->withErrors('Вы уже использовали этот промо-код');

        $request->session()->put('cart_discount_code', $code);

        foreach (Cart::content() as $item) {
            if ($item->options['category'] != 'sales') {
                Cart::update($item->rowid, [
                    'discount' => 0
                ]);
            }
        }

        return redirect()->back()->with('success', 'Промо-код '.strtoupper($code->code).' использован');
    }

    /**
     * Clear cart collection and applied code's session
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear() {
       $this->_cartDestroy();

        return redirect()->to(route('cart'));
    }

    /**
     * Destroy cart instance and forget discount code session
     */
    private function _cartDestroy() {
        Cart::destroy();

        session()->forget('cart_discount_code');
    }

    public static function _cartDiscount($percents = false) {
        $user = \Sentry::getUser();

        $user_discount = isset($user) ? $user->getDiscount() : 0;

        $code_discount = (session()->has('cart_discount_code'))? session('cart_discount_code')->discount: 0;

        $discount = ($user_discount >= $code_discount)? $user_discount: $code_discount;

        if (!$percents) {
            $totalPrice = Cart::total();

            return ceil($totalPrice / 100 * $discount);
        }

        return $discount;
    }

    public static function _itemDiscount($price) {
        return ceil($price / 100 * static::_cartDiscount(true));
    }

}
