<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class ExportProperties extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:properties';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Экспорт всех свойств в файл';

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

        $folder = 'temp_properties';
        
        $categories = \App\Category::orderBy('id', 'ASC')->where('type', '=', 'c')->get();
        
        foreach ($categories as &$category){
            $category->features = \App\Feature::where('category_id', '=', $category->id)->get();
            foreach ($category->features as &$feature){
                $feature->options = \App\Option::where('feature_id', '=', $feature->id)->get();
            }
        }
        
//        print_r($categories);die;
        
        $view = View::make('temp_properties.all', compact('categories'));
        $data = $view->render();
        
        if(!\Storage::disk('public')->exists($folder)){
            \Storage::disk('public')->makeDirectory($folder);
        }
        
        \Storage::disk('public')->put($folder.DIRECTORY_SEPARATOR.'all_properties.html', $data);

    }

}
