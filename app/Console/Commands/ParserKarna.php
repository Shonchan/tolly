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

    protected $manufactures = [
        "Bilge ev tekstil san.ve,tic.ltd/ Турция,Istanbul,Mesıh Pasa Cad.Kiziltas Sok. No:26/2-3 Laleli-Fatih" =>	"KARNA",
        "Bulsan ic dis tic ltd" =>	"BUSLAN",
        "Kayteks kadife san.tic.ltd./ Турция,Denizli Hurriyet Mah.Haci Kandak Cad. No:49 P.K.4 Kayihan" =>	"Acelya",
        "Cetin Tekstil/ Veysel karani Mh. cestepe sk No: 11 Osmangazi/Bursa/Турция" =>	"BIRHOME",
        "Uc Koc tekstil san.tic.ltd/ Турция,Denizli Carsi Mah.Carsi Cad. No:27-29 Buldan" =>	"PUPILLA",
        "Gonca tekstil san.tic.ltd.sti/ Турция,Denizli Pamukkale V.D. 396 005 9683." =>	"GONCA",
        "Ms Tekstil Kon. San. Tic. Ldt. Sti Akhan Mah. 19 Mayıs Cad. No:20 Akkale/ Denizli/Турция" =>	"IRYA",
        "Altinbasak tekstil san.ve.tic.a.s./ Турция,Denizli,Organize Sanayi Bolgesi Turan Bahadir Cad. No:28 " =>	"ALTINBASAK",
        "Befasa Tekstil Kon.San.Tic. / Beyazıt mahallesi, Güler Sokak No:28 Yıldırım/Bursa" =>	"FINEZZA",
        "Ilk-On tekstil / Trakya Serbest Bolgesi 18 K. Baeta 573 Ad:6 Parsel Catalca Istanbul/Турция" =>	"LE VELE"
    ];


    protected $categories_filter = [
        "Пододеяльник - наволочки" => [
           "option" => [ "Размер-для-сайта" => [
                    [
                       "values"=>["Пододеяльник" => 10,
                           "Подушки" => 8,
                           "2 спальный" => 11,
                           "Простынь" => 11,
                       ],
                    ],
                ],
               ],
        ],
        "Одеяла и подушки/LE VELE" => [
           "option" => ["Размер-для-сайта" =>[
                    [
                       "values"=>[
                           "70х70 см" => 2,
                           "195х215 см" => 3,
                           "155x215 см" => 3,
                           ],
                    ],

               ],
           ],

        ],
        "Одеяла и подушки/KARNA" => [
           "option" => ["Размер-для-сайта"=>[
                        [
                           "values"=>[
                               "Подушки" => 2,
                               "Одеяло" => 3
                           ],
                        ],
                   ],
               ],

        ],
        "Для детей" => [
            "option" => [
                    "Размер наволочек"=>[
                        [
                            "values"=>["35x45+5 см*2" => 1],
                        ],
                    ],
                    "Размер-для-сайта"=>[
                        [
                            "values"=>["2 предмета комплект" => 91],
                            "defName" => "Комплект полотенец детский",
                        ],
                    ],

                ],

        ],
        "Полотенца" => [
            "option" => ["Количество полотенец"=>[
                        [
                            "values"=> [
                                "6 шт" => 91,
                                "4 шт" => 91,
                            ],
                            "defName" => "Комплект полотенец",
                        ],
                    ],
                ],


        ],
        "Чехлы" => [
            "option" => ["Вес единицы"=>[
                            [
                                "values" => [
                                    "1,025" => 92,
                                    "1,05" => 92,
                                    "1,405" => 92,
                                    "1,785" => 92,
                                ],
                                "defName" => "Чехол на диван двухместный",
                                "feats" => [
                                    162 => "Диван двухместный",
                                ],

                            ],
                            [
                                "values"=> [
                                    "1,616" => 92,
                                    "1,63" => 92,
                                    "1,75" => 92,
                                    "1,79" => 92,
                                    "2,255" => 92,
                                ],
                                "defName" => "Чехол на диван трехместный",
                                "feats" => [
                                    162 => "Диван трехместный",
                                ],
                            ],
                            [
                                "values"=> [
                                    "3,473" => 92,
                                    "3,9" => 92,
                                    "3,92" => 92,
                                    "4,46" => 92,
                                ],
                                "defName" => "Набор чехлов для дивана 3+1+1",
                                "feats" => [
                                    162 => "Набор диван и 2 кресла",
                                ],
                            ],
                            [
                                "values"=> [
                                    "0,9" => 92,
                                    "0,96" => 92,
                                    "0,966" => 92,
                                    "1" => 92,
                                    "1,305" => 92,
                                ],
                                "defName" => "Чехол на кресло",
                                "feats" => [
                                    162 => "Кресло",
                                ],
                            ],
                            [
                                "values"=> [
                                    "0,403" => 92,
                                    "0,515" => 92,
                                    "0,85" => 92,
                                ],
                                "defName" => "Чехол на стул набор 2 штуки",
                                "feats" => [
                                    162 => "Стул",
                                ],
                            ],
                    ],

                     "name" => [
                            [
                                "values"=> [
                                    "Чехол на диван угловой левосторонний \"BULSAN\" 2+3 посадочных мест" => 92,
                                    "Чехол на диван угловой левосторонний KARNA  \"MILANO\"" => 92,
                                ],
                                "defName" => "Чехол на диван угловой левосторонний",
                                "feats" => [
                                    162 => "Диван угловой",
                                ],
                            ],
                            [
                                "values"=> [
                                    "Чехол на диван угловой правосторонний \"BULSAN\" 2+3 посадочных мест" => 92,
                                    'Чехол на диван угловой правосторонний KARNA "MILANO" ' => 92,
                                ],
                                "defName" => "Чехол на диван угловой правосторонний",
                                "feats" => [
                                    162 => "Диван угловой",
                                ],
                            ],
                        ],

                ],


        ],

    ];

    protected $defNamesExpected = [
        "Комплекты полотенец" => "Комплект полотенец",
        "Полотенца в коробке" => "Полотенце в коробке",
        "Полотенца из бамбука" => "Комплект полотенец",
        "Полотенца" => "Комплект полотенец",
    ];

    protected $priceFilter = [

        "Полотенца в коробке" => 500,
        "Полотенца из бамбука" => 1000,
        "Полотенца" => 600,
    ];

    protected $categories = [
        "Постельное белье" => 1,
//        "Подушки" => 2,
//        "Одеяла" => 3,
        "Пледы" => 4,
        "Покрывала" => 5,
//        "Наволочки" => 8,
        "Наматрасник" => 9,
        "Пододеяльник - наволочки" => 10,
        "Простыни" => 11,
        "Простынь на резинке" => 11,
        "Коврики" => 12,
        "Скатерти" => 15,
        "Комплекты полотенец" => 91,
        "Полотенца в коробке" => 91,
        "Полотенца из бамбука" => 91,
//        "Полотенца" => 91,
//        "Чехлы" => 92,
    ];

    protected $cat_features  = [
        1 => [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,22,151],
        2 => [25,26,27,28,29,30,31,32,33,34,150],
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
        91 => [152,153,154,155,156,157,158,159,160,161],
        92 => [162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,197,198,199,200,201,202,203,204,205,206],
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
        15=>"Скатерть",
        91=>"Комплект полотенец",
        92=>"Чехол",
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
        91=>"TL",
        92=>"CS",
    ];

    protected $features = [
        // 1.Постельное белье
        'Наименование'=>[2,53,64,93,106,116,130,142],
        'Размер-для-сайта'=>[3,55,69,118,133,152],
        'Размер'=>[4,27,40,56,70,83,94,107,117,132,144,153],
        'Размер наволочек'=>[6,73,125],
        'Количество наволочек'=>[7,72,88,124],
        'Пододеяльник'=>[8],
        'Количество пододеяльников'=>[9],
        'Простынь'=>[10],
        'Материал'=>[11],
        'Состав'=>[12,58,74,85,109,120,136,146,157,167],
        'ХарактеристикаНоменклатуры'=>[13,59,76,87,101,111,122,138,148,156,163],
        'Вид застежки'=>[14],
        'Страна'=>[22,34,50,61,78,90,103,113,127,139,149,161,170],
        'Плотность'=>[75,86,110,121,137,147,151,158,166],
        'Пропитка'=>[134],
        'Наполнитель'=>[29,97],
        'Плотность наполнителя'=>[47,98,150],
        'Плотность Ткани верха'=>[99],
        'Ткань верха'=>[30,45,99],
        'Ткань низа'=>[100],
        'Количество полотенец'=>[155],
        'Единица измерения'=>[159,168],
        'Вес единицы'=>[169],
        'Высота спинки от посадочного места (Двухместный)' => [171],
        'Высота юбки (Двухместный)' => [172],
        'Глубина посадочных мест (Двухместный)' => [173],
        'Ширина подлокотников (Двухместный)' => [174],
        'Ширина посадочных мест (Двухместный)' => [175],
        'Высота спинки от посадочного места (Трехместный)' => [176],
        'Высота юбки (Трехместный)' => [177],
        'Глубина посадочных мест (Трехместный)' => [178],
        'Ширина подлокотников (Трехместный)' => [179],
        'Ширина посадочных мест (Трехместный)' => [180],
        'Высота спинки от посадочного места' => [181],
        'Высота юбки' => [182],
        'Глубина посадочных мест' => [183],
        'Ширина подлокотников' => [184],
        'Высота подлокотников длиной стороны' => [185],
        'Высота подлокотников короткой стороны' => [186],
        'Ширина подлокотников длинной стороны' => [187],
        'Ширина подлокотников короткой стороны' => [188],
        'Глубина посадочного места короткой стороны' => [189],
        'Глубина посадочных мест длинной стороны' => [190],
        'Высота спинки от посадочного места длинной стороны' => [191],
        'Высота спинки от посадочного места короткой сторон' => [192],
        'Ширина посадочных мест (длинной стороны)' => [193],
        'Ширина посадочных мест (короткой стороны)' => [194],
        'Ширина посадочных мест длинной стороны' => [195],
        'Ширина посадочных мест короткой стороны' => [196],
        'Высота подлокотников (Кресло)' => [197],
        'Высота спинки от посадочного места (Кресло)' => [198],
        'Высота юбки (Кресло)' => [199],
        'Ширина и глубина посадочного места (Кресло)' => [200],
        'Ширина подлокотников (Кресло)' => [201],
        'Ширина посадочных мест (Стула)' => [202],
        'Ширина спинки (Стула)' => [203],
        'Длина посадочных мест (Стула)' => [204],
        'Высота спинки от посадочного места (Стула)' => [205],
        'Высота юбки (Стула)' => [206],

    ];



    private $cat_limit = [
        1 => 1499,
        2 => 699,
        3 => 799,
        4 => 599,
        5=>799,
        8 => 449,
        9 => 499,
        11 => 549,
        12 => 499,
        15 => 749,
    ];

    protected $cat_multiplier = [
      92 => 1.6,
    ];

    protected $import_file = 'karna.xml';


    protected $column_names = [
        'category'=>'category',
        'external_id'=>'external_id',
        'brand'=>'brand',
        'sku'=>'sku',
        'price'=>'price',
        'optPrice'=>'optPrice',
        'name'=>'name',
        'stock'=>'stock',
        'images'=>'images',
        'body'=>'body',
        'manu'=>'manu',
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
                $xml->optPrice = $attributes->{"Цена"};
                $xml->sku = $attributes->{"Идентификатор"};
                $xml->external_id = $attributes->{"Штрихкод"};
                $xml->images = $attributes->{"Файл"};
                $xml->name = $attributes->{"Наименование"};
                $xml->category = $attributes->{"Категория"};

                $xml->brand = "KARNA";

                foreach ( $xml->{"Свойствы"}->{"Свойство"} as $f ) {
                    $attr = $f->attributes();

                    if($attr->{"Имя"} == "Изготовитель" && isset($this->manufactures[(string)$attr->{"Значение"}])) {
                        $xml->brand = $this->getBrand((string)$attr->{"Значение"});
                        $xml->manu = (string)$attr->{"Значение"};
                    } else {
                        $xml->{$attr->{"Имя"}} = $attr->{"Значение"};
                    }
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

//                if($xml->category == "Чехлы")
//                    print_r($xml);
                $this->items[] = $xml;
//            }

                return true;

            } );
            $reader->parse();
            $reader->close();

//        print_r((array)$this->items[1379]);
//        print_r((array)$this->categories_filter);
//        $it = $this->convert_item((array)$this->items[1379]);
//        print_r($it);
//        return false;

            foreach ( $this->items as $k => $item ) {
                $item = (array)$item;
                unset( $item[ 'param' ] );
                foreach ( $this->items[ $k ]->param as $key => $value ) {
                    $item[ (string)$value->attributes()->name ] = (string)$value;
                }


//                if($item['category'] == "Чехлы") {
//                    print_r( $item );
////                    echo $k."\n";
//                }

                $it = $this->convert_item( $item );


//                if($item['category'] == "Чехлы") {
//                    print_r( $it );
//
//                }

                if ( $it[ 'cat' ] ) {

//                    if($it['cat'] == 91)
//                        print_r($it);
//                die();
//                    if($it['cat'] == 91 || $it['cat'] == 92)
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
        if(isset($item['defName']))
            $name = $item['defName'];
        $shortName = $this->sn[$cat_id];

        // Подготовим товар для добавления в базу


        if(isset($item['sku'])) {
            $variant = Variant::where('sku', '=', trim($item['sku']))->first();
            if($variant) {
                $product_id = $variant->product_id;
                $variant_id = $variant->id;
                $product = Product::find($product_id);
                if($product->enabled == 0)
                    return false;
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

            if(isset($this->cat_multiplier[$cat_id]))
                $variant->price = ceil((float)$this->cat_multiplier[$cat_id]*str_replace(',', '.', str_replace(' ', '', trim($item['optPrice']))));

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

            if(isset($item['priceFilter']) && $item['priceFilter'] > $item['optPrice'])
                return false;

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

        if(isset($this->defNamesExpected[(string)$item['category']])){
            $result["defName"] = $this->defNamesExpected[(string)$item['category']];
        }

        if(isset($this->priceFilter[(string)$item['category']])){
            $result["priceFilter"] = $this->priceFilter[(string)$item['category']];
        }




        if(isset($this->categories_filter[(string)$item['category']])) {
            if(isset($this->categories_filter[(string)$item['category']]['option'])){
                foreach ($this->categories_filter[(string)$item['category']]['option'] as $option => $group) {
//                        print_r($option."\n");
                        if ( isset( $item[ $option ] ) ) {
                            $oval = (string)$item[ $option ];
                            foreach ($group as $filter) {
                                foreach ( $filter[ 'values' ] as $k => $v ) {


                                    if ( $oval === $k ) {
//                                        echo $oval."  -  ".$k."\n";
//                                        echo ($oval===$k)."\n";

                                        $cat = $v;
                                        if ( isset( $filter[ "defName" ] ) ) {
                                            $result[ "defName" ] = $filter[ "defName" ];
                                        }
                                        if ( isset( $filter[ "feats" ] ) ) {
                                          foreach ($filter["feats"] as $fid => $fval){
                                              $result[$fid] = $fval;
                                          }
                                        }
                                    }

                                }
                            }
                        }

                }
            }
        }

        $result['cat'] = $cat;


//        if($result['cat']==10){
//            if($item['Размер-для-сайта'] != 'Пододеяльник')
//                $result['cat'] = '';
//        }

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

    private function getBrand($brand){
        foreach ($this->manufactures as $k=>$v) {

            if (strpos($brand, $k) !== false) {
//                echo $brand." - ".$k."\n";
//                echo strpos($brand, $k)."\n";
                return $v;
            }
        }
        return "KARNA";
    }
}
