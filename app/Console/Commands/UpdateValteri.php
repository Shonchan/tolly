<?php

namespace App\Console\Commands;

use App\Variant;
use Illuminate\Console\Command;

class UpdateValteri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:valteri';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $provider_id = 3;
    protected $url = 'http://api.textiloptom.net/v4/Api/productsVal.json?api_key=';
    protected $api_key = 'a9667b6b6a8260ae1fb2b4562935badd';
    protected $query_url = '';
    protected $categories = [
        "Постельное белье" => 1,
        "Подушки" => 2,
        "Одеяла" => 3,
        "Пледы" => 4,
        "Покрывала" => 5,
        "Наволочки" => 8,
        "Наматрасники" => 9,
        "Пододеяльники" => 10,
        "Коврики" => 12,
        "Простынь на резинке (сатин)" => 11,
        "Простынь на резинке (Софткоттон)" => 11,
        "Простынь на резинке (Джерси)" => 11,
        "Простынь на резинке (бамбук)" => 11,
        "Простынь на резинке (микрофибра)" => 11,
        "Простыни на резинки Сатин печатные (арт. PCT-R)" => 11,
        "Простынь без резинки (Сатин)" => 11,
        "Простынь трикотажная" => 11,
        "Простынь махровая" => 11,
        "Простынь махровая без резинки" => 11,
        "Простыни без резинки Сатин печатные (арт. PCR)" => 11,
        "Простынь АкваСтоп" => 11,
        "Скатерти" => 15,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->query_url = $this->url . $this->api_key;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $page = $this->get_page($this->query_url);
        $items = json_decode($page);

        \DB::table('products')->where('provider_id', '=', $this->provider_id)->update(['checked'=>0]);

        foreach ($items as $it) {

            $it = (array)$it;
            if ( isset( $it[ 'cat_id' ], $this->categories ) || isset( $it[ 'par_cat_id' ], $this->categories ) ) {
                $variant = Variant::where( 'sku', '=', $it[ 'article_and_param' ] )->first();


                if ( $variant ) {
                    $item = [];
                    if ( isset( $it[ 'count_goods' ] ) ) {
                        $item[ 'stock' ] = trim( $it[ 'count_goods' ] );
                    } else {
                        $item[ 'stock' ] = 0;
                    }
                    if ( isset( $it[ 'price' ] ) ) {
                        $item[ 'price' ] = trim( $it[ 'price' ] );
                    }

//                    print_r( $item );

                    $item['updated_at'] = date("Y-m-d H:i:s");

                    \DB::table( 'variants' )->where( 'id', '=', $variant->id )->update( $item );
                    \DB::table('products')->where('id', '=', $variant->product_id)->update(['checked'=>1,'updated_at'=>date("Y-m-d H:i:s")]);


                }
            }
        }


//        \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 0)->update(['enabled'=>0]);
        \DB::table('variants as v')->join('products as p', 'p.id', '=', 'v.product_id')->where('p.provider_id', '=', $this->provider_id)->where('p.checked', '=', 0)->update(['v.stock'=>0]);
//        \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 1)->update(['enabled'=>1]);
    }

    private function get_page($url) {
        $ch = curl_init(); // инициализация


        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // вывод страницы в переменную
        $data = curl_exec($ch); // скачиваем страницу

//        $info = curl_getinfo($ch);
        //print_r($info);
        curl_close($ch);

        return $data;
    }


}
