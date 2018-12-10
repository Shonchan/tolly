<?php

namespace App\Console\Commands;

use App\Brand;
use App\Option;
use App\Product;
use App\Variant;
use App\Api\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ParserAlvitek extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parser:alvitek';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    private $client;
    private $fname = 'alvitek.csv';
    private $fname_c = 'alvitek_c.csv';
    private $column_delimiter = ';';
    private $partner = 47;
    private $provider_id = 1;
    protected $cat_features = [
        1 => [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,22],
        2 => [25,26,27,28,29,30,31,32,33,34],
        3 => [37,38,39,40,41,42,43,44,45,46,47,48,49,50],
        4 => [53,54,55,56,57,58,59,60,61],
        5 => [64,68,69,70,71,72,73,74,75,76,77,78],
        8 => [81,82,83,84,85,86,87,88,89,90],
        9 => [93,94,95,96,97,98,99,100,101,102,103],
        10 => [106,107,108,109,110,111,112,113],
        11 => [116,117,118,119,120,121,122,123,124,125,126,127],
        13 => [116,117,118,119,120,121,122,123,124,125,126,127],
        14 => [116,117,118,119,120,121,122,123,124,125,126,127],
        12 => [142,143,144,145,146,147,148,149],
        15 => [130,131,132,133,134,135,136,137,138,139],

    ];
    private $cat_limit = [
        1 => 1499,
        2 => 499,
        3 => 599,
        4 => 599,
        5 => 799,
        8 => 299,
        9 => 499,
    ];
    private $brands_ignore = ["KAZANOV.A", "Valtery", "СайлиД", "Танго", "Хлопковый Край", "Текстиль репаблик"];
    private $categories = [
        "Комплект постельного белья" => 1,
        "Подушка" => 2,
        "Одеяло" => 3,
        "Плед" => 4,
        "Покрывало" => 5,
        "Наволочка" => 8,
        "Наматрасник" => 9,
        "Пододеяльник" => 10,
        "Простыня на резинке" => 13,
        "Простыня" => 14,
    ];
    private $defNames = [
        1 => "Комплект постельного белья",
        2 => "Подушка",
        3 => "Одеяло",
        4 => "Плед",
        5 => "Покрывало",
        8 => "Наволочка",
        9 => "Наматрасник",
        10 => "Пододеяльник",
        11 => "Простынь",
        12 => "Коврик",
        13 => "Простынь на резинке",
        14 => "Простынь без резинки",
        15 => "Скатерть"
    ];
    private $sn = [
        1 => "BC",
        2 => "CH",
        3 => "CV",
        4 => "PD",
        5 => "CR",
        8 => "PC",
        9 => "MP",
        10 => "DC",
        11=>"SH",
        12=>"CV",
        13=>"SR",
        14=>"SN",
        15=>"SK",
    ];
    private $column_names = [
        'Категория товара' => 'category',
        'ШтрихКод' => 'external_id',
        'Бренд' => 'brand',
        'Артикул' => 'sku',
        'Цена' => 'price',
        'Номенклатура' => 'name',
        'Количество' => 'stock',
        'Фото' => 'images',
        'Описание' => 'body',
    ];
    private $features = [
        'Коллекция' => [2,25,37,53,64,81,93,106,116],
        'Размер' => [3,27,40,56,70,83,94,117],
        'Комплектация' => [5],
        'Размер наволочек' => [6],
        'Размер пододеяльника' => [8,107],
        'Размер простыни' => [10],
        'Ткань' => [11,28,41,57,71,84,95,108,119],
        'Состав ткани' => [12,30,44,74,85,96,109,120],
        'Состав' => [58],
        'Цвет' => [13,32,48,59,76,87,101,111,122],
        'Тип застежки' => [14],
        'Тип застежки наволочки' => [15],
        'Тип застежки пододеяльника' => [16],
        'Тип крепления' => [123],
        'Упаковка' => [17,33,49,60,77,89,102,112,126],
        'Количество в упаковке' => [88],
        'Страна производства' => [22,34,50,61,78,90,103,113,127],
//        'Тип простыни' => [],
//        'Размер подушки' => [],
//        'Материал' => [],
        'Наполнитель' => [29,43,97],
        'Уровень жесткости' => [],
        'Степень теплоты' => [42],
        'Степень поддержки' => [31],
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {


        //если файл есть
//        dd(\Storage::disk('parser')->path(DIRECTORY_SEPARATOR.$this->fname));
        if (\Storage::disk('parser')->exists($this->fname)) {

            \DB::table('products')->where('provider_id', '=', $this->provider_id)->update(['checked'=>0]);


            $this->convert_file();
            
            if( !\Storage::disk('parser')->exists($this->fname_c) )
                return;

            $file_c_path = \Storage::disk('parser')->path($this->fname_c);
            $f = fopen($file_c_path, 'r');
            $columns = fgetcsv($f, null, $this->column_delimiter);
            
            $columns = $this->internal_column_names($columns);
            
            $imported_items = [];

            for($k=0; !feof($f); $k++)
            {
                // Читаем строку
                $line = fgetcsv($f, 0, $this->column_delimiter);
                $product = null;

                if(is_array($line))
                // Проходимся по колонкам строки
                foreach($columns as $i => $col)
                {
                    // Создаем массив item[название_колонки]=значение
                    if(isset($line[$i]) && !empty($line) && !empty($col)){
                        
                        if(is_array($col)){
                            if(isset($this->categories[$product['category']])) {
                                $ta = array_intersect($col, $this->cat_features[$this->categories[$product['category']]]);
                                //print_r($ta);
                                if(count($ta)>0 && !empty($line[$i]))
                                    $product[array_shift($ta)] = $line[$i];
                            }

                        }
                        else
                            $product[$col] = $line[$i];
                    }
                }
                
                // Импортируем этот товар
                if( isset($this->categories[$product['category']]) ){
                    if( $imported_item = $this->import_item($product) )
                        $imported_items[] = $imported_item;
                }
                    
            }
            \DB::table('products')->where('provider_id', '=', $this->provider_id)->where('checked', '=', 0)->update(['enabled'=>0]);
            
        } 
        
    }
    
    protected function import_item($item) {

        $imported_item = new \stdClass;

        // Проверим не пустое ли название и артинкул (должно быть хоть что-то из них)
        if (empty($item['sku']))
            return false;

        $cat_id = $this->categories[$item['category']];
        $name = $this->defNames[$cat_id];
        $shortName = $this->sn[$cat_id];

        // Подготовим товар для добавления в базу
        if (isset($item['sku'])) {
            $variant = Variant::where('sku', '=', trim($item['sku']))->first();
            if ($variant) {
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
        if (!empty($item['brand'])) {
            
            $item['brand'] = trim($item['brand']);
            if(in_array($item['brand'], $this->brands_ignore)) {
                return false;
            }
                
            // Найдем его по имени
            $brand_url = $this->translit(trim($item['brand']));
            $brand = Brand::firstOrCreate(['name' => $item['brand']], ['slug' => $brand_url]);

            $product->brand_id = $brand->id;
        }

        // Если задана категория
        $category_id = (int) $cat_id;
        $categories_ids = array();
        $categories_ids[] = (int) $category_id;

        if (isset($item['variant']))
            $variant->name = trim($item['variant']);

        if (isset($item['sku']))
            $variant->sku = trim($item['sku']);

        $variant->price = 0;
                
        //запрос на получение прайса через апи
        $params = array(
            'type'=>'barcode',
            'value'=>$variant->sku,
            'partner'=>$this->partner
	);

	$this->client->setParams($params);
	$prod = $this->client->makeRequest();

	if(isset($prod['DATA']['PRICE']))
		$variant->price = str_replace(',', '.', str_replace(' ', '', trim($prod['DATA']['PRICE'])));
	if(isset($prod['DATA']['QUANTITY'])){
            if ($item['stock'] == '')
                $variant->stock = null;
            else
                $variant->stock = trim($prod['DATA']['QUANTITY']);
        }

        //если прайс не получен
	if((int)$variant->price == 0 )
            return false;


        if (isset($item['name']))
            $product->external_name = trim($item['name']);

        if (isset($item['body']))
            $product->annotation = trim($item['body']);

        if (isset($item['body']))
            $product->body = trim($item['body']);

        $product->enabled = 1;
        $product->checked = 1;
        $product->provider_id = $this->provider_id;

        if (!empty($item['url']))
            $product->slug = trim($item['url']);
        elseif (!empty($item['name']))
            $product->slug = $this->translit($item['name']);

        if (!empty($variant_id)) {
            $variant->save();
            $product->save();
            $imported_item->status = 'updated';
        }

        $price_limit = isset($this->cat_limit[$category_id]) ? $this->cat_limit[$category_id] : 0;

        if (empty($variant_id) && (int) $variant->stock > 1 && (float) $variant->price >= $price_limit) {
            if (empty($product_id)) {

                $product->save();
                $product_id = $product->id;
            }

            $product->name = $name . ' ' . $shortName . '-' . $product->id;
            $product->slug = $this->translit($product->name);

            $variant->product_id = $product->id;
            $variant->external_id = str_pad($product->id, 7, "0", STR_PAD_LEFT);

            $variant->save();
            $variant_id = $variant->id;
            $imported_item->status = 'added';
        }

        if (!empty($variant_id) && !empty($product_id)) {

            // Добавляем категории к товару
            if (!empty($categories_ids)) {

                $product->categories()->sync($categories_ids);
            }

            //Изображения товаров
            if (isset($item['images'])) {

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
                    // Если нет такого названия колонки, значит это название свойства
                    // Свойство добавляем только если для товара указана категория и непустое значение свойства
                    if ( $category_id && $feature_value !== '' ) {
                        if ( in_array( $feature_id, $this->cat_features[ $category_id ] ) ) {
                            Option::replace( [
                                'product_id' => (int)$product_id,
                                'feature_id' => (int)$feature_id,
                                'value' => $feature_value,
                            ] );
                        }
                    }
                }
            }

            echo $imported_item->status."\n";
            return $imported_item;
        }
    }
    
    protected function generateFileName($file, $path){

        $ext = $image_filename = pathinfo($file, PATHINFO_EXTENSION );
        $filename = Str::random(20);

         while (\Storage::disk('public')->exists($path.$filename.'.'.$ext)) {
                $filename = Str::random(20);
         }

        return $filename.'.'.$ext;
        
    }

    private function translit($text){
        $ru = explode('-', "А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я");
        $en = explode('-', "A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch---Y-y---E-e-YU-yu-YA-ya");

        $res = str_replace($ru, $en, $text);
        $res = preg_replace("/[\s]+/ui", '-', $res);
        $res = preg_replace('/[^\p{L}\p{Nd}\d-]/ui', '', $res);
        $res = strtolower($res);
        return $res;
    }
    
    private function internal_column_names($cols){
        
        foreach ($cols as &$col) {
            if( isset($this->column_names[$col]) )
               $col = $this->column_names[$col];
            
            if( isset($this->features[$col]) )
               $col = $this->features[$col];
        }
        
        return $cols;
        
    }

    private function convert_file() {
        
        // Узнаем какая кодировка у файла
        $file = \Storage::disk('parser')->get($this->fname);

        if (preg_match('//u', $file)) { // Кодировка - UTF8
            // Просто копируем файл
            return \Storage::disk('parser')->put($this->fname_c, $file);
        } else {
            // Конвертируем в UFT8
            $file = $this->win_to_utf($file);
            \Storage::disk('parser')->put($this->fname_c, $file);
        }
        
    }
    
    private function win_to_utf($text){
        
	if(function_exists('iconv'))
	{
            return @iconv('windows-1251', 'UTF-8', $text);
	}
	else
	{
            $t = '';
            for($i=0, $m=strlen($text); $i<$m; $i++)
            {
                $c=ord($text[$i]);
                if ($c<=127) {$t.=chr($c); continue; }
                if ($c>=192 && $c<=207)    {$t.=chr(208).chr($c-48); continue; }
                if ($c>=208 && $c<=239) {$t.=chr(208).chr($c-48); continue; }
                if ($c>=240 && $c<=255) {$t.=chr(209).chr($c-112); continue; }
                if ($c==184) { $t.=chr(209).chr(145); continue; }; #ё
                if ($c==168) { $t.=chr(208).chr(129); continue; }; #Ё
                if ($c==179) { $t.=chr(209).chr(150); continue; }; #і
                if ($c==178) { $t.=chr(208).chr(134); continue; }; #І
                if ($c==191) { $t.=chr(209).chr(151); continue; }; #ї
                if ($c==175) { $t.=chr(208).chr(135); continue; }; #ї
                if ($c==186) { $t.=chr(209).chr(148); continue; }; #є
                if ($c==170) { $t.=chr(208).chr(132); continue; }; #Є
                if ($c==180) { $t.=chr(210).chr(145); continue; }; #ґ
                if ($c==165) { $t.=chr(210).chr(144); continue; }; #Ґ
                if ($c==184) { $t.=chr(209).chr(145); continue; }; #Ґ
            }
            return $t;
	}
    }

}
