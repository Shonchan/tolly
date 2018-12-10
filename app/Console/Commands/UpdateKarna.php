<?php

namespace App\Console\Commands;

use App\Variant;
use Illuminate\Console\Command;

class UpdateKarna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:karna';

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
    protected $url = 'http://www.karnatextile.ru/index.php?option=com_export&task=data&key=3e34a27ef5d00c0a3c5be00dd25e5e49&type=xml';

    protected $provider_id = 4;

    protected $import_file = 'karna.xml';

    protected $categories = [
        "Постельное белье" => 1,
//        "Подушки" => 2,
//        "Одеяла" => 3,
        "Пледы" => 4,
        "Покрывала" => 5,
//        "Наволочки" => 8,
        "Наматрасники" => 9,
        "Пододеяльник" => 10,
        "Простыни" => 11,
        "Простынь на резинке" => 11,
        "Коврики" => 12,
        "Скатерти" => 15,
    ];


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->download_xml_file($this->url, storage_path('files'.DIRECTORY_SEPARATOR.$this->import_file));
        $file = storage_path('files'.DIRECTORY_SEPARATOR.$this->import_file);

        if(\Storage::disk('parser')->exists($this->import_file)) {

            \DB::table( 'products' )->where( 'provider_id', '=', $this->provider_id )->update( [ 'checked' => 0 ] );

            $reader = new \SimpleXMLReader;
            $reader->open( $file );
            $reader->registerCallback( "Товар", function ( $reader ) {
                $xml = $reader->expandSimpleXml();
                $attributes = $xml->attributes();

                if ( $attributes->{"ЕстьВНаличие"} == 'да' ) {
                    $xml->stock = 10;
                } else {
                    $xml->stock = 0;
                }
                $xml->{"ХарактеристикаНоменклатуры"} = $attributes->{"ХарактеристикаНоменклатуры"};
                $xml->price = ceil( $attributes->{"Цена"} * 1.7 );
                $xml->sku = $attributes->{"Идентификатор"};
                $xml->external_id = $attributes->{"Штрихкод"};
                $xml->images = $attributes->{"Файл"};
                $xml->name = $attributes->{"Наименование"};
                $xml->category = $attributes->{"Категория"};
                $xml->brand = "KARNA";

                foreach ( $xml->{"Свойствы"}->{"Свойство"} as $f ) {
                    $attr = $f->attributes();
                    $xml->{$attr->{"Имя"}} = $attr->{"Значение"};

                }
                foreach ( $xml->{"ДополнительныеФайлы"}->{"Файл"} as $f ) {
                    $attr = $f->attributes();
                    $xml->images .= "," . $attr->{"Имя"};

                }

                $this->items[] = $xml;


                return true;

            } );
            $reader->parse();
            $reader->close();


            foreach ( $this->items as $it ) {


                $it = (array)$it;
//            print_r( $it );
                if ( isset( $it[ 'category' ], $this->categories ) ) {
                    $variant = Variant::where( 'sku', '=', $it[ 'sku' ] )->first();


                    if ( $variant ) {
                        $item = [];
                        if ( isset( $it[ 'stock' ] ) ) {
                            $item[ 'stock' ] = trim( $it[ 'stock' ] );
                        } else {
                            $item[ 'stock' ] = 0;
                        }
                        if ( isset( $it[ 'price' ] ) ) {
                            $item[ 'price' ] = trim( $it[ 'price' ] );
                        }

                        $item['updated_at'] = date("Y-m-d H:i:s");
//                        print_r($item);
                        \DB::table( 'variants' )->where( 'id', '=', $variant->id )->update( $item );
                        \DB::table('products')->where('id', '=', $variant->product_id)->update(['checked'=>1,'updated_at'=>date("Y-m-d H:i:s")]);


                    }
                }
            }

//            \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 0)->update(['enabled'=>0]);
            \DB::table('variants as v')->join('products as p', 'p.id', '=', 'v.product_id')->where('p.provider_id', '=', $this->provider_id)->where('p.checked', '=', 0)->update(['v.stock'=>0]);
//            \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 1)->update(['enabled'=>1]);
        }
    }

    protected function download_xml_file($url, $filename)
    {
        $ch = curl_init ();
        curl_setopt ($ch, CURLOPT_URL, $url);
        $fp = fopen ($filename, "w+");
        curl_setopt ($ch, CURLOPT_FILE, $fp);
        curl_setopt ($ch, CURLOPT_REFERER, $url);
        curl_setopt ($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_exec ($ch);
        curl_close ($ch);
    }
}
