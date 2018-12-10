<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ParserKazanova::class,
        Commands\ParserKarna::class,
        Commands\ParserValteri::class,
        Commands\ParserAlvitek::class,
        Commands\ParserAlvitek2::class,
        Commands\UpdateAlvitek::class,
        Commands\UpdateKazanova::class,
        Commands\UpdateValteri::class,
        Commands\UpdateKarna::class,
        Commands\CalculatePopularity::class,
        Commands\CalculateRating::class,
        Commands\MarketProductFeed::class,
        Commands\GoogleProductFeed::class,
        Commands\OrdersStatusCheck::class,
        Commands\ExportProperties::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('calc:popularity')
                  ->hourly();
        $schedule->command('market:feed')
            ->hourly();
        $schedule->command('google:feed')
            ->hourly();
        $schedule->command('update:alvitek')
            ->hourly();
        $schedule->command('update:valteri')
            ->hourly();
        $schedule->command('update:karna')
            ->hourly();
        $schedule->command('update:kazanova')
            ->hourly();

        $schedule->command('parser:kazanova')->daily();
        $schedule->command('parser:valteri')->daily();
        $schedule->command('parser:karna')->daily();
        $schedule->command('parser:alvitek2')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
