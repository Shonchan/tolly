<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Api\Client;

class UpdateAlvitek extends Command
{

    private $client;
    private $partner = 47;
    private $provider_id = 1;


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:alvitek';

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
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        \DB::table('products')->where('provider_id', '=', $this->provider_id)->update(['checked'=>0]);

        $variants = \DB::table('variants as v')->join('products as p', 'p.id', '=', 'v.product_id')
                        ->select('v.id', 'v.sku','v.price', 'v.stock', 'v.product_id')->where('p.provider_id', '=', $this->provider_id)
                        ->where('p.enabled', '=', 1)->get();

        foreach ($variants as $variant) {


            $item = [];
            //запрос на получение прайса через апи
            $params = array(
                'type' => 'article',
//                'type' => 'barcode',
                'value' => $variant->sku,
                'partner' => $this->partner
            );

            $this->client->setParams( $params );
            $prod = $this->client->makeRequest();

            $cat_id = \DB::table('products_categories')->select('category_id')->where('product_id', '=', $variant->product_id)->first();


            if ( isset( $prod[ 'DATA' ][ 'PRICE' ] ) ) {
                $item[ 'price' ] = str_replace( ',', '.', str_replace( ' ', '', trim( $prod[ 'DATA' ][ 'PRICE' ] ) ) );
                if($cat_id->category_id == 1) {
                    $item['compare_price'] =  floor($item['price']*1.5) ;
                }
            }
            if ( isset( $prod[ 'DATA' ][ 'QUANTITY' ] ) ) {
                $item['stock'] = trim( $prod[ 'DATA' ][ 'QUANTITY' ] );
            } else {
                $item['stock'] = 0;
            }

            if ( isset( $prod[ 'DATA' ][ 'ARTICLE' ] ) )
                $variant->sku = trim( $prod[ 'DATA' ][ 'ARTICLE' ] );




            $item['updated_at'] = date("Y-m-d H:i:s");

//            \DB::table( 'variants' )->where( 'id', '=', $variant->id )->update( [ 'sku' => $variant->sku ] );
            \DB::table( 'variants' )->where( 'id', '=', $variant->id )->update( $item );

            \DB::table('products')->where('id', '=', $variant->product_id)->update(['checked'=>1,'updated_at'=>date("Y-m-d H:i:s")]);

        }

//        \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 0)->update(['enabled'=>0]);
        \DB::table('variants as v')->join('products as p', 'p.id', '=', 'v.product_id')->where('p.provider_id', '=', $this->provider_id)->where('p.checked', '=', 0)->update(['v.stock'=>0]);

//        \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 1)->update(['enabled'=>1]);

//        $params = array(
//            'type'=>'article',
//            'value'=>$variant->sku,
//            'partner'=>$this->partner
//        );
//
//        $this->client->setParams($params);
//        $prod = $this->client->makeRequest();
//
//        print_r($prod);
    }
}
