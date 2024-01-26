<?php

namespace App\Console;

use App\Models\SmsNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->call(function () {
            $smsData = [
                'user'=>'first_name'.' '.'last_name'.' '.'surname',
                'phone'=>'7524478855',
                'message_text'=>'Vasha zayavka byla odobrena',
                'status'=> 'pending',
                'message_id'=> '2445574452588',

            ];
        $smsRequest = SmsNotification::firstOrCreate($smsData);
        })->dailyAt('11:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
