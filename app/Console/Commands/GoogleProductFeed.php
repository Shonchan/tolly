<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class GoogleProductFeed extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерация товарного фида';

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
    public function handle() {

        $variants   = \App\Variant::whereHas('product', function($q){
                $q->where('enabled', '=', 1);
           })
           ->where('stock', '>', 0)
           ->get();
        
        $categories = \App\Category::get();

        foreach($variants as &$variant){
            
            $folder = 'feed';
            
            $opts = \DB::table('options as o')
            ->leftJoin('features as f', 'f.id', '=', 'o.feature_id')
            ->selectRaw('f.id as feature_id, f.name, o.value, o.product_id')
            ->whereIn('o.product_id', (array)$variant->product->id)
            ->orderBy('f.position', 'asc')->get();

            $options = [];
            $description = [];
            
            foreach ( $opts as $opt ) {
                $description[] = "{$opt->name}: {$opt->value}";
                $options[] = [
                    'name' => $opt->name,
                    'value' => $opt->value
                ];
            }
            
            $variant->options = $options;
            $variant->description = implode(', ', $description);

            $variant->product->image = "";
            $images = json_decode( $variant->product->images );
            if(isset($images[0]))
                $variant->product->image = $variant->product->imgSize500($images[0]);
        }
        
        $view = View::make('feeds.all_products_google', compact('categories', 'variants', 'date'));
        $xml = "<?xml version=\"1.0\"?>\n" .$view->render();
        
        if(!Storage::disk('public')->exists($folder)){
            Storage::disk('public')->makeDirectory($folder);
        }
        
        Storage::disk('public')->put($folder.DIRECTORY_SEPARATOR.'google.xml', $xml);

    }

}
