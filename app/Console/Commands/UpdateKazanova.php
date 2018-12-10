<?php

namespace App\Console\Commands;

use App\Variant;
use Illuminate\Console\Command;

class UpdateKazanova extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:kazanova';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    protected $url = 'http://www.kazanova-textil.ru/bitrix/catalog_export/for_partners.php';

    protected $provider_id = 2;

    protected $import_file = 'kazanova.xml';

    protected $categories = [
        2707=>1,2803=>1,2804=>1,2805=>1,2806=>1,2838=>1,2839=>1,2708=>1,2834=>1,2835=>1,2836=>1,2837=>1,2709=>1,2710=>1,2711=>1,2712=>1,2713=>1,2714=>1,2715=>1,2796=>1,2816=>1,2716=>1,2717=>1,2718=>1,2719=>1,2720=>1,2721=>1,2722=>1,2723=>1,2724=>1,2725=>1,2726=>1,2727=>1,2728=>1,2729=>1,2730=>1,2792=>1,2793=>1,2807=>1,2794=>1,2801=>1,2802=>1,2787=>1,2812=>1,2813=>1,2814=>1,2818=>1,2821=>1,2819=>1,2820=>1,2731=>1,2732=>1,2733=>1,2734=>1,2735=>1,2736=>1,2737=>1,2738=>1,2739=>1,2740=>1,2741=>1,2742=>1,2743=>1,2744=>1,2745=>1,2746=>1,2747=>1,2748=>1,2749=>1,2750=>1,2751=>1,2830=>1,2752=>1,2753=>1,2754=>1,2755=>1,2756=>1,2757=>1,2758=>1,
        2688=>2,2689=>2,2690=>2,2691=>2,2692=>2,2693=>2,2823=>2,
        2851=>3,2952=>3,2667=>3,2668=>3,2669=>3,
        2670=>4,2671=>4,2672=>4,2673=>4,2674=>4,2675=>4,2676=>4,2677=>4,2678=>4,2679=>4,2680=>4,2681=>4,2682=>4,2683=>4,2684=>4,2685=>4,2686=>4,2687=>4,2840=>4,
        2694=>5,2811=>5,2832=>5,2797=>5,2831=>5,2695=>5,2696=>5,2697=>5,2698=>5,2699=>5,2700=>5,2701=>5,2702=>5,2703=>5,2704=>5,2705=>5,2706=>5,
        2648=>8,2649=>8,2650=>8,2651=>8,
//        2653=>11,2654=>11,2655=>11,2656=>11,2657=>11,
        2652=>12,
        2780=>15,2795=>15,2798=>15,2800=>15,2799=>15,2781=>15,2782=>15,2783=>15,2784=>15,2785=>15,2786=>15,

    ];


    protected $items;

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
            $reader->registerCallback( "offer", function ( $reader ) {
                $xml = $reader->expandSimpleXml();
                $attributes = $xml->attributes();

                if ( $attributes->{"available"} == 'true' ) {
                    $xml->stock = 1;
                } else {
                    $xml->stock = 0;
                }
                $xml->id = (int)preg_replace( "/\D/", "", $attributes->{"id"} );
                if ( isset( $this->categories[ (int)$xml->categoryId ] ) && (int)$xml->quantity > 1 ) {

                    $this->items[] = $xml;
                }

                return true;

            } );

            $reader->parse();
            $reader->close();

            foreach ( $this->items as $k => $it ) {
                $it = (array) $it;
                $variant = Variant::where('sku', '=', trim($it['vendorCode']))->first();


                if($variant) {
                    $item = [];
                    if ( isset( $it[ 'quantity' ] ) ) {
                        $item[ 'stock' ] = trim( $it[ 'quantity' ] );
                    } else {
                        $item[ 'stock' ] = 0;
                    }
                    if ( isset( $it[ 'price' ] ) ) {
                        $item[ 'price' ] = trim( $it[ 'price' ] );
                    }

                    $item['updated_at'] = date("Y-m-d H:i:s");

                    \DB::table( 'variants' )->where( 'id', '=', $variant->id )->update( $item );
                    \DB::table('products')->where('id', '=', $variant->product_id)->update(['checked'=>1,'updated_at'=>date("Y-m-d H:i:s")]);

                    echo $variant->id."\n";
                } else {
                    echo $it['vendorCode']."\n";
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
