<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\SmsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

$smsData = [
    'user'=>'first_name'.' '.'last_name'.' '.'surname',
    'phone'=>'7524478855',
    'message_text'=>'Vasha zayavka byla odobrena',
    'status'=> 'pending',
    'message_id'=> '2445574452588',

];
$smsRequest = SmsNotification::firstOrCreate($smsData);


//class SendSmsBefore implements ShouldQueue
//{
//    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
//
//    /**
//     * Create a new job instance.
//     */
//    public function __construct()
//    {
//        //
//    }
//
//    /**
//     * Execute the job.
//     */
//    public function handle(): void
//    {
//        $threeDaysLater = date ('Y-m-d', strtotime ('+3 day'));
//
//
//    }
//}
