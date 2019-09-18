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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\AuctionImport::class,
        \App\Console\Commands\AuctionMedia::class,
        \App\Console\Commands\AuctionFullImport::class,
        \App\Console\Commands\AuctionRemoveExpired::class,
        \App\Console\Commands\MigrateLive::class,
        \App\Console\Commands\MigrateDev::class,
        \App\Console\Commands\CleanImages::class,
        \App\Console\Commands\CleanVehicleMakes::class,
        \App\Console\Commands\VehicleMakeImport::class,
        \App\Console\Commands\CleanImageMedia::class,
        \App\Console\Commands\RenewMediaQueueImages::class,
        \App\Console\Commands\VehicleSlugs::class,
        \App\Console\Commands\VehicleCounty::class,
        \App\Console\Commands\StuckSchedule::class,
        \App\Console\Commands\VehicleCountLog::class,
        \App\Console\Commands\CleanDrafts::class,
        \App\Console\Commands\feedTypeAdjust::class,
        \App\Console\Commands\swapPrices::class,
        \App\Console\Commands\MatchVehicles::class,
        \App\Console\Commands\MatchVehicleModels::class,
        \App\Console\Commands\CronTest::class,
        \App\Console\Commands\AwsBounce::class,
        \App\Console\Commands\AwsComplaint::class,
        \App\Console\Commands\Importer::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('quantum:activityClean')->daily();
        $schedule->command('quantum:expiredMembership')->everyThirtyMinutes();
        $schedule->command('quantum:postPublish')->everyTenMinutes();
        $schedule->command('quantum:firewall')->everyFiveMinutes();
        $schedule->command('gauk:importMedia')->everyTenMinutes()->withoutOverlapping();
        //$schedule->command('gauk:import')->hourly()->withoutOverlapping();
        $schedule->command('gauk:expireAuctions')->hourly()->withoutOverlapping();
        //$schedule->command('quantum:stuckschedule')->hourly();
        $schedule->command('gauk:cleanDrafts')->daily();
        $schedule->command('quantum:newsletterSendShots')->everyFiveMinutes();
        $schedule->command('quantum:newsletterSendResponders')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('quantum:newsletterCountOpened')->everyThirtyMinutes()->withoutOverlapping();
        $schedule->command('gauk:matchVehicles')->everyFifteenMinutes()->withoutOverlapping();
        $schedule->command('quantum:newsletterImportQueue')->everyFifteenMinutes()->withoutOverlapping();
        $schedule->command('gauk:processAwsBounces')->hourly()->withoutOverlapping();
        $schedule->command('gauk:processAwsComplaints')->daily()->withoutOverlapping();
        $schedule->command('quantum:calendarDaily')->daily()->withoutOverlapping();
        $schedule->command('gauk:importer features')->daily()->withoutOverlapping();
        $schedule->command('gauk:importer categories')->daily()->withoutOverlapping();
        $schedule->command('gauk:importer dealers')->hourly()->withoutOverlapping();
        $schedule->command('gauk:importer parsedDealers')->hourlyAt(45)->withoutOverlapping();
        $schedule->command('gauk:importer getLots')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
