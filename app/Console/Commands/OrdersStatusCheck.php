<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OrdersStatusCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:checkstatuse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Обновление статуса заказов
     *
     * @return mixed
     */
    public function handle(){
        
        $apiUrl     = \Config::get('sberbank-api.apiUrl');
        $token      = \Config::get('sberbank-api.token');
        $api        = new \App\Api\SberbankMerchantAPI($apiUrl, $token);
        
        //берем все не оплаченные заказы
        $orders = \App\Order::where('api_payment_id', '!=', '')->where('paid', '=', 1)->get();

        foreach ($orders as $order){
            
            $args = array(
                'orderId' => $order->api_payment_id,
            );

            $result = $api->getState($args);
                
            if ($result->paymentAmountInfo->paymentState == 'DEPOSITED'){
                $order->paid = 2;
                $order->update();
            }
            
        }
        
    }
}
