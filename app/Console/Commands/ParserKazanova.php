<?php

namespace App\Console\Commands;

use App\Brand;
use App\Option;
use App\Product;
use App\Variant;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ParserKazanova extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:kazanova';

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

    protected $url = 'http://www.kazanova-textil.ru/bitrix/catalog_export/for_partners.php';

    protected $provider_id = 2;

    protected $categories = [
        1571=>1,1572=>1,1573=>1,1624=>1,1625=>1,1638=>1,1682=>1,1710=>1,1711=>1,1758=>1,1778=>1,1779=>1,1780=>1,1783=>1,1786=>1,1787=>1,1788=>1,1883=>1,1884=>1,1885=>1,1833=>1,1834=>1,1835=>1,1841=>1,1842=>1,1844=>1,1878=>1,1879=>1,1880=>1,1871=>1,1873=>1,1874=>1,1914=>1,1915=>1,1916=>1,1917=>1,1918=>1,1919=>1,2308=>1,2309=>1,
        1454=>2,1599=>2,1888=>2,1889=>2,
        1575=>3,1577=>3,1909=>3,
        1448=>4,1740=>4,1741=>4,1742=>4,1846=>4,1847=>4,1894=>4,
        1602=>5,1769=>5,1770=>5,1869=>5,1830=>5,
        1420=>8,1689=>8,
        1837=>14,1838=>14,
        // 1903=>12,

    ];

    protected $cat_features  = [
        1 => [1,2,3,4,5,6,7,8,9,10,11,12,13,14,74],
        2 => [15,16,17,18,19,20,21,22],
        3 => [23,25,26,27,28,29,30,31],
        4 => [32,34,35,37,38],
        5 => [39,41,42,43,44,45,46,47],
        8 => [48,49,50,51,52,53],
        10 => [61,62,63,64,65,66],
        13 => [67,68,69,70,71,72,73],
        14 => [67,68,69,70,71,72,73],
    ];

    protected $defNames = [
        1=>"Комплект постельного белья",
        2=>"Подушка",
        3=>"Одеяло",
        4=>"Плед",
        5=>"Покрывало",
        8=>"Наволочка",
        9=>"Наматрасник",
        10=>"Пододеяльник",
        11=>"Простынь",
        13=>"Простынь на резинке",
        14=>"Простынь без резинки",
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
        11=>"SH",
        13=>"SR",
        14=>"SN",
    ];

    protected $features = [
        // 1.Постельное белье
        'model'=>[1,15,23,32,39,48,54,61,67],
        'Размер для сайта'=>[2,16,25,34,41,49,55,62,68],
        'Комплектация для сайта'=>[3],
        'Материал для сайта'=>[7,17,26,35,42,50,56,63,69],
        'Состав для сайта'=>[8],
        'Наполнитель'=>[18,28],
        'Комплектация для сайта'=>[43],
        'Цвет для сайта'=>[9,20,29,36,45,51,58,64,70],

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

    protected $import_file = 'kazanova.xml';


    protected $column_names = [
        'categoryId'=>'category',
        'Код'=>'external_id',
        'vendor'=>'brand',
        'vendorCode'=>'sku',
        'price'=>'price',
        'ВидНоменклатуры'=>'name',
        'quantity'=>'stock',
        'picture'=>'images',
        'description'=>'body',
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

        $reader = new \SimpleXMLReader;
        $reader->open($file);
        $reader->registerCallback("offer", function($reader) {
            $xml = $reader->expandSimpleXml();
            $attributes = $xml->attributes();

            if ($attributes->{"available"} == 'true') {
                $xml->stock = 1;
            } else {
                $xml->stock = 0;
            }
            $xml->id = (int) preg_replace("/\D/", "", $attributes->{"id"});
            if(isset($this->categories[(int)$xml->categoryId]) && (int)$xml->quantity > 1) {
//                $this->info($xml->model);
                $this->items[] = $xml;
            }

            return true;

        });
        $reader->parse();
        $reader->close();

//        $this->info(count($this->items));

        foreach ($this->items as $k=>$item) {
            $item = (array)$item;
            unset($item['param']);
            foreach ($this->items[$k]->param as $key => $value) {
                $item[(string)$value->attributes()->name] = (string)$value;
            }

            $it = $this->convert_item($item);


            if(isset($this->categories[$it['category']])) {
                $this->import_item($it);
//                exit();
            }

        }

    }

    protected function import_item($item)
    {
//        global $simpla, $defNames, $sn, $column_names, $categories, $cat_features, $cat_limit, $provider_id;
        $imported_item = new \stdClass;

        // Проверим не пустое ли название и артинкул (должно быть хоть что-то из них)
        if(empty($item['sku']))
            return false;


        $cat_id = $this->categories[$item['category']];
        $name = $this->defNames[$cat_id];
        $shortName = $this->sn[$cat_id];

        // Подготовим товар для добавления в базу
//        $product = array();

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
//
            $product->brand_id = $brand->id;
        }

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


                $product->enabled = 1;

                $product->provider_id = $this->provider_id;


                if(!empty($item['url']))
                    $product->slug = trim($item['url']);
                elseif(!empty($item['name']))
                    $product->slug = $this->translit($item['name']);


        if(!empty($variant_id)) {
            $variant->save();
            $product->save();
            $imported_item->status = 'updated';
        }

        $price_limit = isset($this->cat_limit[$category_id]) ? $this->cat_limit[$category_id] : 0;

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

//            dd($variant);
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
                /*foreach($categories_ids as $c_id) {

                    \DB::insert(DB::raw('INSERT IGNORE INTO products_categories SET product_id= :pid, category_id= :cid,'), [
                        'pid'=>$product_id,
                        'cid'=>$c_id,
                    ]);
                }*/
//


            // Изображения товаров
            if(isset($item['images']) && $imported_item->status != 'updated')
            {
                $imgs = [];
                // Изображений может быть несколько, через запятую
                $images = explode(',', $item['images']);
                foreach($images as $image)
                {
                    $image = trim($image);


                    if(!empty($image))
                    {
                        // Имя файла
                        $path = 'products'.DIRECTORY_SEPARATOR.date('mY').DIRECTORY_SEPARATOR;
                        $filename = $this->generateFileName($image, $path);

                        if(!\Storage::disk('public')->exists($path)){
                            \Storage::disk('public')->makeDirectory($path);
                        }

                        copy($image, \Storage::disk('public')->path($path.$filename));
                        $imgs[] = $path.$filename;
                        // Добавляем изображение только если такого еще нет в этом товаре
//                        $simpla->db->query('SELECT filename FROM __images WHERE product_id=? AND (filename=? OR filename=?) LIMIT 1', $product_id, $image_filename, $image);
//                        if(!$simpla->db->result('filename'))
//                        {
//                            $simpla->products->add_image($product_id, $image);
//                            $simpla->image->download_image($image);
//                        }





                    }
                }
                $product->images = json_encode($imgs);
                $product->save();
            }
            // Характеристики товаров
            foreach($item as $feature_id=>$feature_value)
            {
                // Если нет такого названия колонки, значит это название свойства

                // Свойство добавляем только если для товара указана категория и непустое значение свойства
                if($category_id && $feature_value!=='')
                {
                    if(in_array($feature_id, $this->cat_features[$category_id]))
                    {
                        // $this->db->query('SELECT f.id FROM __features f WHERE f.name=? LIMIT 1', $feature_name);
                        // if(!$feature_id = $this->db->result('id'))
                        //  $feature_id = $this->features->add_feature(array('name'=>$feature_name));
                        Option::replace([
                                'product_id'=> (int)$product_id,
                                'feature_id'=>(int)$feature_id,
                                'value'=>$feature_value,
                            ]);
//                        \DB::insert(DB::raw('REPLACE INTO options SET SET product_id= :pid, feature_id= :fid, value= :val'), [
//                            'pid'=>(int)$product_id,
//                            'fid'=>(int)$feature_id,
//                            'val'=>$feature_value,
//                        ]);

//                        $simpla->features->add_feature_category((int)$feature_id, (int)$category_id);
//                        $simpla->features->update_option((int)$product_id, (int)$feature_id, $feature_value);
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
//        global $column_names,$features, $categories, $cat_features;
        $result = [];
        foreach ($item as $key => $value) {
            if(isset($this->column_names[$key]))
                $result[$this->column_names[$key]] = $value;
            if(isset($this->features[$key]) && isset($this->cat_features[$this->categories[$result['category']]])) {
                $ta = array_intersect($this->features[$key], $this->cat_features[$this->categories[$result['category']]]);
                if(count($ta)>0 && !empty($value))
                    $result[array_shift($ta)] = $value;
            }
        }
        return $result;
    }
}
