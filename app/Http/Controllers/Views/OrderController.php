<?php

namespace App\Http\Controllers\Views;

use App\Delivery;
use App\Order;
use App\Purchase;
use App\Variant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function cart()
    {
        $cart = null;
        if(isset($_COOKIE['shopping_cart'])) {
//            \Debugbar::info($_COOKIE[ 'shopping_cart' ]);
            $cart = json_decode( $_COOKIE[ 'shopping_cart' ] );

//            $total = $cart['total'];

            $total = 0;
            $ids = array_keys((array)$cart);

//            \Debugbar::info($cart);
            $variants = Variant::whereIn('id', $ids)->get();
            foreach ($variants as &$v) {
                $vid = $v->id;
                $v->amount = $cart->$vid;
                $total += $v->amount*$v->price;
            }

        }

        return view('order.cart', compact(['cart', 'variants', 'total']));
    }

    public function create(Request $request)
    {
       $purchases = $request->get('variants');

       $vars = Variant::whereIn('id', array_values($purchases['id']))->get();
       $variants = [];
        foreach ( $vars as $variant ) {
            $variants[$variant->id] = $variant;
       }
       $total_amount = 0;
       $total_sum = 0;
       foreach ($purchases['id'] as $k=>$v) {
           $total_amount += $purchases['amount'][$k];
           $total_sum += $purchases['amount'][$k]*$variants[$v]->price;
           $variants[$v]->amount = $purchases['amount'][$k];
       }

        return view('order.create', compact(['variants', 'total_amount', 'total_sum']));
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'midname' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/cart')
                ->withErrors($validator)
                ->withInput();
        }

        $delivery = Delivery::where('id', '=', $request->get('delivery_method_id'))->first();

        $data = $request->all();
        $order = new Order();
        $order->name = implode(' ', [$data['name'], $data['midname'], $data['lastname']]);
        $order->address  = implode(' ', array_values($data['address']));
        $order->phone = $data['phone'];
        $order->email = $data['email'];
        $order->comment = $data['comment'];
        $order->status = 1;
        $order->slug = md5(microtime());
//        $order->ip = ;
        $order->total_price = $data['total_sum'];
        $order->payment_method_id = $data['payment_method_id'];
        $order->delivery_id = $data['delivery_method_id'];
        $order->delivery_price = $delivery->price;

        $order->save();

        $purchases = $request->get('purchases');

        $vars = Variant::whereIn('id', array_values($purchases['id']))->get();
        $variants = [];
        foreach ( $vars as $variant ) {
            $variants[$variant->id] = $variant;
        }
        $total_amount = 0;
        $total_sum = 0;
        foreach ($purchases['id'] as $k=>$v) {
            $total_amount += $purchases['amount'][$k];
            $total_sum += $purchases['amount'][$k]*$variants[$v]->price;
            $variants[$v]->amount = $purchases['amount'][$k];
        }

        foreach($variants as $v) {
            $purchase = new Purchase();
            $purchase->order_id = $order->id;
            $purchase->variant_id = $v->id;
            $purchase->product_id = $v->product->id;
            $purchase->product_name = $v->product->name;
            $purchase->variant_name = $v->name;
            $purchase->price = $v->price;
            $purchase->amount = $v->amount;
            $purchase->sku = $v->sku;
            $purchase->save();
        }

        return view('order.show', compact(['order']));


    }

    public function show($hash)
    {

    }

    public function addToCart(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'variant_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/cart')
                ->withErrors($validator)
                ->withInput();
        }

        $v_id = $request->get('variant_id');

        if(isset($_COOKIE['shopping_cart'])) {
            $cart = json_decode( $_COOKIE[ 'shopping_cart' ] );
            if(isset($cart[$v_id])){
                $cart[$v_id] += 1;
            } else {
                $cart[$v_id] = 1;
            }
        } else {
            $cart = [];
            $cart[$v_id] = 1;

        }


        return redirect('/cart')->withCookie(cookie('shopping_cart', json_encode($cart), 30*24*60, '/'));

    }
}
