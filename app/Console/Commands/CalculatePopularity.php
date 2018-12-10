<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CalculatePopularity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calc:popularity';

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $max = \DB::table('products')
            ->selectRaw('max(id) as max_id')
            ->first();

        $date = date("Y-m-d H:i:s", strtotime('-14 days'));
//        echo $date;
        \DB::table('bonuses')
            ->where('created_at', '<', $date)
            ->delete();

        $min_id = 0;
        $chunk = 100;
        $geo_id = 1;
        while($min_id < $max->max_id){
//            $ids = \DB::table('products')
//                ->selectRaw('id')
//                ->where('id', '>', $min_id)
//                ->where('id', '<=', $min_id + $chunk)
//                ->get();
//            $min_id += $chunk;
//            foreach ($ids as $id) {
                $bonuses = \DB::table('bonuses')
                    ->selectRaw('product_id, geo_id, sum(bonus) as sum')
                    ->where('product_id', '>', $min_id)
                    ->where('id', '<=', $min_id + $chunk)
                    ->groupBy('product_id', 'geo_id')
                    ->get();
                $min_id += $chunk;
//                echo($bonuses);
                foreach ($bonuses as $b){
                    \DB::insert("INSERT INTO popularity (geo_id, product_id, weight) VALUES ($b->geo_id, $b->product_id, $b->sum)
                                ON DUPLICATE KEY UPDATE weight = $b->sum");
                }

//            }
        }
    }
}
