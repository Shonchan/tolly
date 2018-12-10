<?php

namespace App\Console\Commands;

use App\Brand;
use App\Option;
use App\Product;
use App\Variant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ParserKarna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:karna';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse named provider';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $url = 'http://www.karnatextile.ru/index.php?option=com_export&task=data&key=3e34a27ef5d00c0a3c5be00dd25e5e49&type=xml';

    protected $provider_id = 4;

    protected $categories = [
        "Постельное белье" => 1,
//        "Подушки" => 2,
//        "Одеяла" => 3,
        "Пледы" => 4,
        "Покрывала" => 5,
//        "Наволочки" => 8,
        "Наматрасники" => 9,
        "Пододеяльник - наволочки" => 10,
        "Простыни" => 11,
        "Простынь на резинке" => 11,
        "Коврики" => 12,
        "Скатерти" => 15,
    ];

    protected $cat_features  = [
        1 => [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,22],
        2 => [25,26,27,28,29,30,31,32,33,34],
        3 => [37,38,39,40,41,42,43,44,45,46,47,48,49,50],
        4 => [53,54,55,56,57,58,59,60,61],
        5 => [64,68,69,70,71,72,73,74,75,76,77,78],
        8 => [81,82,83,84,85,86,87,88,89,90],
        9 => [93,94,95,96,97,98,99,100,101,102,103],
        10 => [106,107,108,109,110,111,112,113],
        11 => [116,117,118,119,120,121,122,123,124,125,126,127],
//        13 => [116,117,118,119,120,121,122,123,124,125,126,127],
//        14 => [116,117,118,119,120,121,122,123,124,125,126,127],
        12 => [142,143,144,145,146,147,148,149],
        15 => [130,131,132,133,134,135,136,137,138,139],
    ];

    protected $defNames = [
        1=>"Постельное белье",
        2=>"Подушка",
        3=>"Одеяло",
        4=>"Плед",
        5=>"Покрывало",
        8=>"Наволочка",
        9=>"Наматрасник",
        10=>"Пододеяльник",
        11=>"Простынь",
        12=>"Коврик",
        13=>"Простынь на резинке",
        14=>"Простынь без резинки",
        15=>"Скатерть"
    ];

    protected $sn = [
        1=>"BC",
        2=>"CH",
        3=>"CV",
        4=>"PD",
        5=>"CR",
        8=>"PC",
        9=>"MP",
        10=>"DC",
        11=>"SR",
        13=>"SR",
        12=>"CV",
        14=>"SN",
        15=>"SK",
    ];

    protected $features = [
        // 1.Постельное белье
        'Наименование'=>[2,53,64,93,106,116,130,142],
        'Размер-для-сайта'=>[3,55,69,118,133],
        'Размер'=>[4,56,70,94,107,117,132,144],
        'Размер наволочек'=>[6,73,125],
        'Количество наволочек'=>[7,72,124],
        'Пододеяльник'=>[8],
        'Количество пододеяльников'=>[9],
        'Простынь'=>[10],
        'Материал'=>[11],
        'Состав'=>[12,58,74,109,120,136,146],
        'ХарактеристикаНоменклатуры'=>[13,59,76,101,111,122,138,148],
        'Вид застежки'=>[14],
        'Страна'=>[22,61,78,103,113,127,139,149],
        'Плотность'=>[75,110,121,137,147],
        'Пропитка'=>[134],
        'Наполнитель'=>[97],
        'Плотность наполнителя'=>[98],
        'Ткань верха'=>[99],
        'Ткань низа'=>[100],



    ];

    protected $cat_limit = [
        1=>1499,
        2=>499,
        3=>599,
        4=>599,
        5=>799,
        8=>299,
        9=>499,
    ];

    protected $import_file = 'karna.xml';


    protected $column_names = [
        'category'=>'category',
        'external_id'=>'external_id',
        'brand'=>'brand',
        'sku'=>'sku',
        'price'=>'price',
        'name'=>'name',
        'stock'=>'stock',
        'images'=>'images',
        'body'=>'body',
    ];

    protected $items;


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

            \DB::table('products')->where('provider_id', '=', $this->provider_id)->update(['checked'=>0]);

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
//                print_r($attr->{"Имя"});
                }
                foreach ( $xml->{"ДополнительныеФайлы"}->{"Файл"} as $f ) {
                    $attr = $f->attributes();
                    $xml->images .= "," . $attr->{"Имя"};
//                print_r($attr->{"Имя"});
                }
//            exit();


//            $xml->id = (int) preg_replace("/\D/", "", $attributes->{"id"});
//            if(isset($this->categories[(int)$xml->categoryId]) && (int)$xml->quantity > 1) {

                $this->items[] = $xml;
//            }

                return true;

            } );
            $reader->parse();
            $reader->close();

//        print_r((array)$this->items[0]);
//        $it = $this->convert_item((array)$this->items[350]);
//        print_r($it);

            foreach ( $this->items as $k => $item ) {
                $item = (array)$item;
                unset( $item[ 'param' ] );
                foreach ( $this->items[ $k ]->param as $key => $value ) {
                    $item[ (string)$value->attributes()->name ] = (string)$value;
                }

                $it = $this->convert_item( $item );

//            echo $it['cat']."\n";

                if ( $it[ 'cat' ] ) {
//                    print_r($it);
//                die();
                    $this->import_item( $it );

                }

            }


//            \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 0)->update(['enabled'=>0]);
            \DB::table('variants as v')->join('products as p', 'p.id', '=', 'v.product_id')->where('p.provider_id', '=', $this->provider_id)->where('p.checked', '=', 0)->update(['v.stock'=>0]);
//            \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 1)->update(['enabled'=>1]);

        }

    }

    protected function import_item($item)
    {

        $imported_item = new \stdClass;

        // Проверим не пустое ли название и артинкул (должно быть хоть что-то из них)
        if(empty($item['sku']))
            return false;


        $cat_id = $item['cat'];
        $name = $this->defNames[$cat_id];
        $shortName = $this->sn[$cat_id];

        // Подготовим товар для добавления в базу


        if(isset($item['sku'])) {
            $variant = Variant::where('sku', '=', trim($item['sku']))->first();
            if($variant) {
                $product_id = $variant->product_id;
                $variant_id = $variant->id;
                $product = Product::find($product_id);
            } else {
                $variant = new Variant();
                $product = new Product();
            }

        } else {
            $product = new Product();
            $variant = new Variant();
        }


        // Если задан бренд
        if(!empty($item['brand']))
        {
            $item['brand'] = trim($item['brand']);

            if($item['brand'] == 'KAZANOV.A. / Казанова')
                $item['brand'] = 'KAZANOV.A';
            // Найдем его по имени
            $brand_url = $this->translit(trim($item['brand']));

            $brand = Brand::firstOrCreate(['name'=>$item['brand']], ['slug'=>$brand_url]);

            $product->brand_id = $brand->id;
        }

//        echo $brand->nme."\n";

        // Если задана категория
        $category_id = (int)$cat_id;
        $categories_ids = array();
        $categories_ids[] = (int)$category_id;



            if(isset($item['variant']))
                $variant->name = trim($item['variant']);

            if(isset($item['sku']))
                $variant->sku = trim($item['sku']);

            if(isset($item['price']))
                $variant->price = str_replace(',', '.', str_replace(' ', '', trim($item['price'])));

            if(isset($item['compare_price']))
                $variant->compare_price = trim($item['compare_price']);

            if(isset($item['stock']))
                if($item['stock'] == '')
                    $variant->stock = null;
                else
                    $variant->stock = trim($item['stock']);


                if(isset($item['name']))
                    $product->external_name = trim($item['name']);



                if(isset($item['body']))
                    $product->annotation = trim($item['body']);

                if(isset($item['body']))
                    $product->body = trim($item['body']);


                if(empty($variant_id)) {
                    $product->enabled = 1;
                }
                $product->checked = 1;

                $product->provider_id = $this->provider_id;


                if(!empty($item['url']))
                    $product->slug = trim($item['url']);
                elseif(!empty($item['name']))
                    $product->slug = $this->translit($item['name']);

//        print_r($variant);


        if(!empty($variant_id)) {
            $variant->save();
            $product->save();
            $imported_item->status = 'updated';
        }

        $price_limit = isset($this->cat_limit[$category_id]) ? $this->cat_limit[$category_id] : 0;

//        echo $price_limit."\n";
        if(empty($variant_id)  && (int)$variant->stock > 1 && (float)$variant->price >= $price_limit)
        {
            if(empty($product_id)) {

                $product->save();
                $product_id = $product->id;

            }

            $product->name = $name.' '.$shortName.'-'.$product->id;
            $product->slug = $this->translit($product->name);


            $variant->product_id = $product->id;
            $variant->external_id = str_pad($product->id, 7, "0", STR_PAD_LEFT);


            $variant->save();
            $variant_id = $variant->id;
            $imported_item->status = 'added';
        }



        if(!empty($variant_id) && !empty($product_id))
        {

            // Добавляем категории к товару
            if(!empty($categories_ids)) {

                $product->categories()->sync( $categories_ids );

            }



            // Изображения товаров
            if(isset($item['images']))
            {

                $update_image = true;
                if($imported_item->status == 'updated') {
                    $temp_imgs = json_decode($product->images);
                    if(\Storage::disk('public')->exists( $temp_imgs[0])){
                        $update_image = false;
                    }

                }

                if($update_image) {

                    $imgs = [];
                    // Изображений может быть несколько, через запятую
                    $images = explode( ',', $item[ 'images' ] );
                    foreach ( $images as $image ) {
                        $image = trim( $image );


                        if ( !empty( $image ) ) {
                            // Имя файла
                            $path = 'products' . DIRECTORY_SEPARATOR . date( 'mY' ) . DIRECTORY_SEPARATOR;
                            $filename = $this->generateFileName( $image, $path );

                            if ( !\Storage::disk( 'public' )->exists( $path ) ) {
                                \Storage::disk( 'public' )->makeDirectory( $path );
                            }

                            try {
                                if ( copy( $image, \Storage::disk( 'public' )->path( $path . $filename ) ) ) {
                                    $imgs[] = $path . $filename;
                                } else {
                                    throw new \Exception($image.' не доступен');
                                }
                            } catch (\Exception $e) {
                                echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                            }



                        }
                    }
                    $product->images = json_encode( $imgs );
                    $product->save();
                }
            }

            if($imported_item->status != 'updated') {
                // Характеристики товаров
                foreach ( $item as $feature_id => $feature_value ) {


                    // Свойство добавляем только если для товара указана категория и непустое значение свойства
                    if ( $category_id && $feature_value !== '' ) {
                        if ( in_array( $feature_id, $this->cat_features[ $category_id ] ) ) {

                            Option::replace( [
                                'product_id' => (int)$product_id,
                                'feature_id' => (int)$feature_id,
                                'value' => $feature_value,
                            ] );
//                        \DB::insert(DB::raw('REPLACE INTO options SET SET product_id= :pid, feature_id= :fid, value= :val'), [
//                            'pid'=>(int)$product_id,
//                            'fid'=>(int)$feature_id,
//                            'val'=>$feature_value,
//                        ]);


                        }

                    }
                }
            }
            return $imported_item;
        }


    }

    protected function generateFileName($file, $path)
    {



        $ext = $image_filename = pathinfo($file, PATHINFO_EXTENSION );

        $filename = Str::random(20);


         while (\Storage::disk('public')->exists($path.$filename.'.'.$ext)) {
                $filename = Str::random(20);
         }


        return $filename.'.'.$ext;
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

    protected function translit($text)
    {

        $ru = explode('-', "А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я");
        $en = explode('-', "A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch---Y-y---E-e-YU-yu-YA-ya");

        $res = str_replace($ru, $en, $text);
        $res = preg_replace("/[\s]+/ui", '-', $res);
        $res = preg_replace('/[^\p{L}\p{Nd}\d-]/ui', '', $res);
        $res = strtolower($res);
        return $res;
    }

    protected function convert_item($item)
    {

        $result = [];
        if(isset($item['category']) && isset($this->categories[(string)$item['category']]))
            $cat = $this->categories[(string)$item['category']];
        else {
            $cat = false;
            foreach ($this->categories as $c => $v) {
                if(isset($item['category']) && strpos((string)$item['category'], $c) === 0)
                    $cat = $v;
            }
        }
        $result['cat'] = $cat;
        if($result['cat']==10){
            if($item['Размер-для-сайта'] != 'Пододеяльник')
                $result['cat'] = '';
        }

//        print_r($this->categories[(string)$item->category]);

        foreach ($item as $key => $value) {

            if(isset($this->column_names[$key]))
                $result[$this->column_names[$key]] = $value;

            if(isset($this->features[$key]) && $cat && isset($this->cat_features[$cat])) {
                $ta = array_intersect($this->features[$key], $this->cat_features[$cat]);
                if(count($ta)>0 && !empty($value))
                    $result[array_shift($ta)] = $value;
            }
        }


        return $result;
    }
}
