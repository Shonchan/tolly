<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Review;
use App\Rating;

class CalculateRating extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calc:rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Расчет рейтинга';

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

        //берем все id продуктов для которых есть отзывы
        $productIds = Review::select()->groupBy('product_id')->pluck('product_id')->toArray();
        
        foreach ($productIds as $pId){
            
            $rating         = Review::getRating($pId);
            $count_reviews  = Review::getCountReviews($pId);
            
            $rating = Rating::updateOrCreate(['product_id' => $pId], [
                'product_id'    => $pId,
                'rating'        => $rating,
                'count_reviews' => $count_reviews
            ]);
            
        }
        
    }

}
