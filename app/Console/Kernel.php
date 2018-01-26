<?php

namespace App\Console;

use App\Console\Commands\GetOrdersMercando;
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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SubirBaseEnvios::class,
        \App\Console\Commands\GetProducts::class,
        \App\Console\Commands\GetOrders::class,
        \App\Console\Commands\GetOrdersMercando::class,
        \App\Console\Commands\GetCustomers::class,
        \App\Console\Commands\MercadoPago::class,
        \App\Console\Commands\Metafields::class,
        \App\Console\Commands\GiftsCards::class,
        \App\Console\Commands\GetProductsMercando::class,
        \App\Console\Commands\GetUsers::class,
        \App\Console\Commands\UpdatePoints::class,
        \App\Console\Commands\UpdatePointsMercando::class,
        \App\Console\Commands\UpdateEmail::class,
        \App\Console\Commands\SendMails::class,
        \App\Console\Commands\SetTransactions::class,
        \App\Console\Commands\Compresion::class,
        \App\Console\Commands\CustomerDisabled::class,
        \App\Console\Commands\SendBond::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        //$schedule->command('subirbd')->everyFiveMinutes();

        $schedule->command('get:products')
                 ->twiceDaily(1, 20);

        $schedule->command('get:products-mercando')
                 ->twiceDaily(2, 21);

        //$schedule->command('get:customers')->twiceDaily(2, 21);

        $schedule->command('get:orders')
                 ->twiceDaily(14, 23);

        $schedule->command('get:orders-mercando')
            ->twiceDaily(14, 23);

        $schedule->command('get:update-points')->dailyAt('01:30');
        $schedule->command('get:update-points-mercando')->hourly();

        //$schedule->command('get:metafields')->dailyAt('23:30');

        //$schedule->command('get:giftscards')->monthly();
    }
}
