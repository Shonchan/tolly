<?php

namespace App\Http\Controllers\Views;

use App\Delivery;
use App\Order;
use App\PaymentMethod;
use App\Purchase;
use App\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Discounts;
use App\Coupons;

class OrderController extends Controller
{

    protected $productController;

    private $months =  [
        "01" => "января",
        "02" => "февраля",
        "03" => "марта",
        "04" => "апреля",
        "05" => "мая",
        "06" => "июня",
        "07" => "июля",
        "08" => "августа",
        "09" => "сентября",
        "10" => "октября",
        "11" => "ноября",
        "12" => "декабря"
    ];

    public function __construct(ProductController $productController)
    {
        $this->productController = $productController;
    }

    public function cart()
    {
        $cart = null;
        $vids = [];
        $total = 0;
        $discountValue = 0;
        if(isset($_COOKIE['shopping_cart'])) {
//            \Debugbar::info($_COOKIE[ 'shopping_cart' ]);
            $cart = json_decode( $_COOKIE[ 'shopping_cart' ] );

//            $total = $cart['total'];

            $total = 0;
            $total_amount = 0;
            $ids = array_keys((array)$cart);

//            \Debugbar::info($cart);
            $variants = Variant::whereIn('id', $ids)->get();

            $imgs = \DB::table('products as p')
                ->leftJoin('variants as v', 'p.id', '=', 'v.product_id')
                ->selectRaw('p.images, v.id' )
                ->whereIn('v.id', $ids)->get();

            $images = [];
            foreach ($imgs as $i){
                if(isset(json_decode($i->images)[0]))
                 $images[$i->id] = json_decode($i->images)[0];
                 else
                     $images[$i->id] = "";
            }

            $vids = [];
            foreach ($variants as &$v) {
                $vid = $v->id;
                $v->amount = $cart->$vid;
                $v->image = $this->productController->imgSize(320, 200, $images[$vid]);
                if($v->amount > $v->stock)
                    $v->amount = $v->stock;
                $total += $v->amount*$v->price;
                $total_amount += $v->amount;
                $vids[] = $vid;
//                \Debugbar::info($total);
            }

            $discountValue = 0;
            $couponeCode = '';

            $couponeId = \Cookie::get('coupone');

            if($couponeId){
                $coupon = Coupons::where('id', '=', $couponeId)->first();

                if($coupon){
                    $couponeCode = $coupon->code;
                    $discountValue = Discounts::calculate ($total, $coupon->value, $coupon->type_id);
                }

            }

        }

        $freeDelivery = 3000;

        $date = Carbon::now();

//        \Debugbar::info($date->addHours(12)->addMinutes(20));

        $date->addHours(12)->addMinutes(20)->addDay();



        $dateDelivery = $this->ru_month($date->format('j m'));



        return view('order.cart', compact(['cart', 'variants', 'total', 'total_amount', 'discountValue', 'couponeCode', 'freeDelivery', 'dateDelivery', 'vids']));
    }

    public function create(Request $request)
    {
       $purchases = $request->get('variants');

       $vars = Variant::whereIn('id', array_values($purchases['id']))->get();

        $imgs = \DB::table('products as p')
            ->leftJoin('variants as v', 'p.id', '=', 'v.product_id')
            ->selectRaw('p.images, v.id' )
            ->whereIn('v.id', array_values($purchases['id']))->get();

        $images = [];
        foreach ($imgs as $i){
            $images[$i->id] = json_decode($i->images)[0];
        }

       $variants = [];
        foreach ( $vars as $variant ) {

                $variants[ $variant->id ] = $variant;
                $variants[ $variant->id ]->image = $this->productController->imgSize( 320, 200, $images[ $variant->id ] );

       }
       $total_amount = 0;
       $total_sum = 0;
       foreach ($purchases['id'] as $k=>$v) {
           if($purchases['amount'][$k] > $variants[$v]->stock)
               $purchases['amount'][$k] = $variants[$v]->stock;

           $total_amount += $purchases['amount'][$k];
           $total_sum += $purchases['amount'][$k]*$variants[$v]->price;
           $variants[$v]->amount = $purchases['amount'][$k];
           if($variants[$v]->stock == 0)
               unset($variants[$v]);
       }

       $discountValue = 0;
       $couponeId = \Cookie::get('coupone');

        //если в куках есть купон
        if($couponeId){
            $coupon = Coupons::where('id', '=', $couponeId)->first();

            if($coupon){
                $discountValue = Discounts::calculate ($total_sum, $coupon->value, $coupon->type_id);
            }

        }

       $pay_methods = PaymentMethod::all();
       $del_methods = Delivery::all();

        $freeDelivery = 3000;

        $date = Carbon::now();
        $date->addHours(12)->addMinutes(20)->addDay();
        $dateDelivery = $this->ru_month($date->format('j m'));


        return view('order.create', compact(['variants', 'total_amount', 'total_sum', 'pay_methods', 'del_methods', 'discountValue', 'freeDelivery', 'dateDelivery']));
    }

    /**
     * Сохранение заказа
     * @param Request $request
     * @return type
     */
    public function store(Request $request)
    {

        //коннект к БД CRM
        $crm = \DB::connection('crm');

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required|regex:/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/',
            'email' => 'nullable|email',
        ],
        [
            'phone.regex'=> 'Укажите полный номер телефона',
            'email.email'=> 'Укажите корректный email',
//            'name.required'=> 'Укажите имя',
//            'lastname.required'=> 'Укажите фамилию',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'validate_error'=>true,
                'validate_phone_message' => $errors->first('phone'),
                'validate_email_message' => $errors->first('email'),
//                'validate_name_message' => $errors->first('name'),
//                'validate_lastname_message' => $errors->first('lastname'),
//                'errors'=> $errors
            ]);

        }

        $delivery = Delivery::where('id', '=', $request->get('delivery_method_id'))->first();

        $data = $request->all();

//        $fio = explode(' ', $data['name']);
        $firstname = $data['name'] ?? '';
        $lastname = $data['lastname'] ?? '';
        $patronymic = '';

        //ищем клиента по номеру телефона в crm
        $phone = Order::formatPhoneToNumber($data['phone']);
        $client = $crm->table('clients')->where('phone', '=', $phone)->first();

        //если не найден
        if(!$client){
           $crm->table('clients')->insert(
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'patronymic' => $patronymic,
                    'phone' => $phone
                ]
            );
           $client = $crm->table('clients')->where('phone', '=', $phone)->first();
        }

        $order = new Order();
        $order->name =  $data['name'];
        $order->lastname =  $data['lastname'];
        $order->address  = implode(' ', array_values($data['address']));
        $order->phone = $data['phone'];
        $order->email = $data['email'];
        $order->comment = $data['comment'];
//        if($data['recipient'])
//            $order->comment .= "\nПолучатель: ".$data['recipient'];
        $order->status = 1;
        $order->slug = md5(microtime());
//        $order->ip = ;
        $order->total_price = $data['total_sum'];
        $order->payment_method_id = $data['payment'] ?? "";
        $order->delivery_id = $data['delivery'] ?? "";
        $order->delivery_price =  $data['delivery_price'];
        $order->client_id =  $client->id;

        $order->save();


        //скидка----------------------------------------------------------------------
        $couponeId = \Cookie::get('coupone');

        //если в куках есть купон
        if($couponeId){
            $coupon = Coupons::where('id', '=', $couponeId)->first();

            if($coupon){
                $discounts = new Discounts();
                $discounts->order_id = $order->id;
                $discounts->value = $coupon->value;
                $discounts->comment = "Купон {$coupon->code}";
                $discounts->type_id = $coupon->type_id;
                $discounts->save();
            }

        }

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

        $geo_id =1;

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
            \DB::table('bonuses')->insert([
                'geo_id' => $geo_id,
                'product_id' => $v->product_id,
                'bonus' => 45,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

//        $cookie = \Cookie::forget('shopping_cart');


        \Cookie::queue(\Cookie::forget('shopping_cart'));
        \Cookie::queue(\Cookie::forget('coupone'));

        return response()->json(['url'=>url('order', ['hash'=>$order->slug])]);

    }
    
    /**
     * страница на которую идет редирект после оплаты, для проверки статуса
     * @param Request $request
     * @return type
     */
    public function check(Request $request){

        $apiUrl     = \Config::get('sberbank-api.apiUrl');
        $token      = \Config::get('sberbank-api.token');
        
        if($request->has('orderId')){
            
            $status = '';

            $order = Order::where('api_payment_id', '=', $request->get('orderId'))->first();
            
            //если заказ не найден, редирект на главную
            if(!$order)
                return redirect('');
            
            //если заказ не оплачен
            if($order->paid == 1){
                
                $api = new \App\Api\SberbankMerchantAPI($apiUrl, $token);

                $args = array(
                    'orderId' => $order->api_payment_id,
                );

                $result = $api->getState($args);
                
                $status = $result->paymentAmountInfo->paymentState;

                //оплачен
                if ($status== 'DEPOSITED'){
                    $order->paid = 2;
                    $order->update();
                }
                
            }
            
        }
        
        return redirect("/order/{$order->slug}");
        
    }

    /**
     * Просмотр заказа
     * @param type $hash
     * @return type
     */
    public function show($hash)
    {
        $apiUrl     = \Config::get('sberbank-api.apiUrl');
        $token      = \Config::get('sberbank-api.token');
        $api        = new \App\Api\SberbankMerchantAPI($apiUrl, $token);
        
        $order = \DB::table('orders as o')
                ->leftJoin('deliveries as d', 'd.id', '=', 'o.delivery_id')
                ->leftJoin('payment_methods as pm', 'pm.id', '=', 'o.payment_method_id')
                ->leftJoin('statuses as s', 's.id', '=', 'o.status')
                ->selectRaw('o.*, d.name dname, pm.name pmname, s.name sname, s.id sid')
                ->where('o.slug', '=', $hash)
                ->first();
        
        //если такого заказа нет, на главную
        if(!$order)
            return redirect('');
        
        $purchases = \DB::table('purchases as ps')
                ->leftJoin('variants as v', 'v.id', '=', 'ps.variant_id')
                ->leftJoin('products as pr', 'pr.id', '=', 'ps.product_id')
                ->selectRaw('ps.product_name product_name, v.name vname, ps.price, ps.amount, pr.images images')
                ->where('ps.order_id', '=', $order->id)
                ->get();
        
        $countPurchases = count($purchases);

        //итоговая сумма
        $totalSumm = 0;
        foreach ($purchases as &$purchase){
            $purchase->image = $this->productController->imgSize(320, 200, json_decode($purchase->images)[0]);
            $totalSumm += $purchase->price * $purchase->amount;
        }
        
        //скидка
        $discountValue = 0;
        $discount = Discounts::where('order_id', '=', $order->id)->first();
        if($discount)
            $discountValue = Discounts::calculate ($totalSumm, $discount->value, $discount->type_id);
        
        //если есть id платежа из апи и заказ не оплачен(у нас), проверяем статус в апи
        if(isset($order->api_payment_id) && $order->paid == 1){

            $args = array(
                'orderId' => $order->api_payment_id,
            );

            $result = $api->getState($args);

            //оплачен
            if ($result->paymentAmountInfo->paymentState == 'DEPOSITED'){

                $order->paid = 2;
                Order::where('id', '=', $order->id)->update(['paid' => 2]);

            }
        }
        
        return view('order.show', compact(['order', 'purchases', 'countPurchases', 'totalSumm', 'discountValue']));
    }
    
    /**
     * Покупка
     * @param Request $request
     * @return type
     */
    public function pay(Request $request)
    {
        $apiUrl     = \Config::get('sberbank-api.apiUrl');
        $token      = \Config::get('sberbank-api.token');
        $paymentUrl = \Config::get('sberbank-api.paymentUrl');
        
        if($request->has('slug')){
            
            $api = new \App\Api\SberbankMerchantAPI($apiUrl, $token);
            
            $order = Order::where('slug', '=', $request->get('slug'))->first();
            
            //сумма всех позиций
            $totalSumm = \DB::table('purchases')
                ->selectRaw('sum(price * amount) total')
                ->where('order_id', '=', $order->id)
                ->value('total'); 
            
            //скидка
            $discountValue = 0;
            $discount = Discounts::where('order_id', '=', $order->id)->first();
            if($discount)
                $discountValue = Discounts::calculate ($totalSumm, $discount->value, $discount->type_id);
            
            //итоговая сумма включая скидку
            $amount = ($totalSumm - $discountValue + $order->delivery_price) * 100;
            
           
            $order->api_hash_id = $order->generateEcApiId();
            $order->api_payment_id = "";
            $order->save();

            $args = [
                'orderNumber' => $order->api_hash_id,
                'amount' => $amount,
                'returnUrl' => "http://dev.tolly.ru/order/check",
            ];

            $result = $api->geristerOrder($args);

            //добавляем уникальный ид заказа из апи и редиректим на форму оплаты
            if(isset($result->orderId)){
                $order->api_payment_id = $result->orderId;
                $order->update();
                return redirect($result->formUrl);
            }

//            //если есть id заказа из апи и заказ не оплачен(у нас), проверяем статус в апи
//            if($order->api_payment_id && $order->paid == 1){
//                
//                $args = array(
//                    'orderId' => $order->api_payment_id,
//                );
//
//                $result = $api->getState($args);
//                
//                //еслли не оплачен, редиректим на страницу оплаты
//                if($result->paymentAmountInfo->paymentState == 'CREATED'){
//                    return redirect($paymentUrl.$order->api_payment_id);
//                }
//
//            }
//
//            return redirect()->back();

        }

    }
    
    public function applyСouponeAjax(Request $request)
    {
        $result['error'] = false;
        $total = 0;
        $total_amount = 0;
        $discountValue = 0;

        $couponeCode    = $request->get('coupone');

        $cook = \Cookie::get('shopping_cart');



        if(!empty($cook)){
            $cart = json_decode( $cook );

            $ids = array_keys((array)$cart);

            $variants = Variant::whereIn('id', $ids)->get();

            foreach ($variants as &$v) {
                $vid = $v->id;
                $v->amount = $cart->$vid;
                if($v->amount > $v->stock)
                    $v->amount = $v->stock;
                $total += $v->amount*$v->price;
                $total_amount += $v->amount;

            }
        }


        //если корзина не пуста и не пуст код купона
        if(!empty($cook) && !empty($couponeCode)) {



            $coupone = Coupons::where('code', '=', $couponeCode)->first();

            if($coupone){
                $discountValue = Discounts::calculate ($total, $coupone->value, $coupone->type_id);
//                $result['discount'] = $discountValue;
//                $result['total'] = $total;
                $result['message'] = "Теперь ваш заказ стоит на $discountValue руб. дешевле!";
                $result['view'] =   view('layouts.basket-coupon', compact(['total', 'total_amount', 'discountValue']))->render();

                return response()->json($result)->withCookie(cookie('coupone', $coupone->id, 30*24*60, '/', '', false, false));
            } else {
                $result['error'] = true;
                $result['view'] =   view('layouts.basket-coupon', compact(['total', 'total_amount', 'discountValue']))->render();
                $result['message'] = 'Купон не найден';
                \Cookie::queue(\Cookie::forget('coupone'));
            }

        }

        if(empty($couponeCode) || $couponeCode == ''){
            $result['error'] = true;
            $result['view'] =   view('layouts.basket-coupon', compact(['total', 'total_amount', 'discountValue']))->render();
            $result['message'] = 'Введите купон';
            \Cookie::queue(\Cookie::forget('coupone'));
        }

        return response()->json($result)->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

    }

    //пересчет скидки
    public function recalculateDiscountAjax(Request $request)
    {
        $couponeId = \Cookie::get('coupone');
        $cook = \Cookie::get('shopping_cart');
        $discountValue = 0;
        $couponeCode = '';
        $total = 0;
        $total_amount = 0;

        if($cook){

            $cart = json_decode( $cook );

            $ids = array_keys((array)$cart);

            $variants = Variant::whereIn('id', $ids)->get();

            foreach ($variants as &$v) {
               $vid = $v->id;
               $v->amount = $cart->$vid;
               if($v->amount > $v->stock)
                    $v->amount = $v->stock;
               $total += $v->amount*$v->price;
               $total_amount += $v->amount;

            }

            if($couponeId)
                $coupon = Coupons::where('id', '=', $couponeId)->first();

            if(isset($coupon)){
                $couponeCode = $coupon->code;
                $discountValue = Discounts::calculate ($total, $coupon->value, $coupon->type_id);
            }

        }

//        \Debugbar::info($variants);

        //если товаров в корзине нет
        if($total == 0)
            \Cookie::queue(\Cookie::forget('coupone'));

        $freeDelivery = 3000;
        $date = Carbon::now();
        $dateDelivery = $this->ru_month($date->addHours(12)->addMinutes('20')->addDay()->format('j m'));

        $data = view('layouts.basket-sidebar', compact(['total', 'total_amount', 'discountValue', 'couponeCode', 'freeDelivery', 'dateDelivery']))->render();
        return response()->json($data)->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

//        return response()->json([
//            'discount'    => $discountValue
//        ]);

    }

    public function selectVariantAjax(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required',
            'variant_id' => 'required',
        ]);

        if ($validator->fails()) {
            return false;
        }

        $p_id = $request->get('product_id');
        $v_id = $request->get('variant_id');

        $vars = Variant::where('product_id', '=', $p_id)
                        ->where('stock', '>', 0)->get();

        $img = \DB::table('products as p')
            ->selectRaw('p.images' )
            ->where('p.id', '=', $p_id)->first();


        $image = json_decode($img->images)[0];

        $variants = [];
        foreach ($vars as &$v) {


            $var = new \stdClass();
            $var->id = $v->id;
            $var->external_id = $v->external_id;
            $var->name = $v->name;
            $var->stock = $v->stock;
            $var->price = $v->price;
            $var->imageUrl =  $this->productController->imgSize(320, 200, $image);
            $variants[] = $var;
            if ($v_id == $var->id) {
                $variant = $var;
                $variant->product_name = $v->product->name;
            }

        }



//        $data = view('layouts.basket-blank', compact(['variants']))->render();
//        return response()->json($data);
        return view('layouts.basket-blank', compact(['variants', 'variant']));


    }

    public function addToCartAjax(Request $request)
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
        $cook = \Cookie::get('shopping_cart');

        if(!empty($cook)) {
            $cart = json_decode( $cook );


//            \Debugbar::info($cart);
            if(isset($cart->$v_id)){
                $cart->$v_id += 1;
            } else {
                $cart->$v_id = 1;
            }
        } else {
            $cart = new \stdClass();
            $cart->$v_id = 1;

        }

        $variant = Variant::where('id', '=', $v_id)->first();

        $geo_id =1;
        $cook = \Cookie::get('added_to_cart');
        if(!empty($cook)) {
            $added = json_decode( $cook );
        }   else {
            $added = [];
        }

        if(!in_array($variant->product_id, $added)) {
            \DB::table( 'bonuses' )->insert( [
                'geo_id' => $geo_id,
                'product_id' => $variant->product_id,
                'bonus' => 15,
                'created_at' => date( 'Y-m-d H:i:s' ),
                'updated_at' => date( 'Y-m-d H:i:s' ),
            ] );
//            $added[] = $variant->product_id;
//            session(['clicked'=>$added]);
        }

        return response()->json([
            'cart' => $cart,
            'v_count' => $cart->$v_id,
        ])->withCookie(cookie('shopping_cart', json_encode($cart), 30*24*60, '/', '', false, false));

    }

//    public function addToCart(Request $request)
//    {
//        $validator = \Validator::make($request->all(), [
//            'variant_id' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            return redirect('/cart')
//                ->withErrors($validator)
//                ->withInput();
//        }
//
//        $v_id = $request->get('variant_id');
//        $cook = \Cookie::get('shopping_cart');
//
//        if(!empty($cook)) {
//            $cart = json_decode( $cook );
//
//
////            \Debugbar::info($cart);
//            if(isset($cart->$v_id)){
//                $cart->$v_id += 1;
//            } else {
//                $cart->$v_id = 1;
//            }
//        } else {
//            $cart = [];
//            $cart[$v_id] = 1;
//
//        }
//
//        $variant = Variant::where('id', '=', $v_id)->first();
//
//        $geo_id =1;
//        \DB::table('bonuses')->insert([
//            'geo_id' => $geo_id,
//            'product_id' => $variant->product_id,
//            'bonus' => 15,
//            'created_at' => date('Y-m-d H:i:s'),
//            'updated_at' => date('Y-m-d H:i:s'),
//        ]);
//
//
//        return redirect('/cart')->withCookie(cookie('shopping_cart', json_encode($cart), 30*24*60, '/', '', false, false));
//
//    }

    public function callback(Request $request){

        $data = $request->all();
        $validator = \Validator::make($data, [
            'name'  => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'validate_error' => true,
            ]);
        }

        $order = new Order();
        $order->name = $data['name'];
        $order->phone = $data['phone'];
        $order->note = 'Обратный звонок';
        $order->status = 1;
        $order->slug = md5(microtime());
        if($order->save()){

            $message = "{$data['name']}, мы перезвоним вам в рабочее время";

            $time = time() - strtotime('today');
            if($time >= 32400 && $time < 75599){
                $message = "{$data['name']}, мы скоро с вами свяжемся";
            }

            return response()->json([
                'validate_error' => false,
                'message' => $message,
            ]);
        }

    }


    public function by_one_click(Request $request){

        //коннект к БД CRM
        $crm = \DB::connection('crm');

        $data = $request->all();
        $validator = \Validator::make($data, [
            'name'  => 'required',
            'phone' => 'required|regex:/^\+7\s\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/',
            'count' => 'required',
            'vid'   => 'required',
        ],
        [
            'phone.required'=> 'Введите номер телефона',
            'phone.min'     => 'Введите полный номер телефона',
            'name.required' => 'Введите  имя',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'validate_error' => true,
                'validate_messages' => response()->json($errors->all()),
            ]);
        }

        //ищем клиента по номеру телефона в crm
        $phone = Order::formatPhoneToNumber($data['phone']);
        $client = $crm->table('clients')->where('phone', '=', $phone)->first();

        $fio = preg_split('/\s+/', $data['name']);
        $firstname = $fio[0] ?? '';
        $lastname = $fio[1] ?? '';
        $patronymic = $fio[2] ?? '';

        //если не найден
        if(!$client){
           $crm->table('clients')->insert(
                [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'patronymic' => $patronymic,
                    'phone' => $phone
                ]
            );
           $client = $crm->table('clients')->where('phone', '=', $phone)->first();
        }

        $variant = Variant::where('id', $data['vid'])->first();
        $product = \App\Product::where('id', $variant->product_id)->first();

        $order = new Order();
        $order->name = $data['name'];
        $order->phone = $data['phone'];
        $order->note = 'Заказ в 1 клик';
        $order->status = 1;
        $order->total_price = $variant->price * $data['count'];
        $order->slug = md5(microtime());
        $order->client_id =  $client->id;
        $order->save();

        $purchase = new Purchase();
        $purchase->order_id = $order->id;
        $purchase->variant_id = $variant->id;
        $purchase->product_id = $variant->product_id;
        $purchase->price = $variant->price;
        $purchase->amount = $data['count'];
        $purchase->sku = $variant->sku;
        $purchase->product_name = $product->name;

        if($purchase->save()){

            $message = "Заказ № {$order->id} оформлен";

            return response()->json([
                'validate_error' => false,
                'message' => $message,
            ]);
        }

    }

    private function ru_month($date)
    {
        $part = explode(' ', $date);
        $part[1] = $this->months[$part[1]];
        return implode(' ', $part);
    }

}
