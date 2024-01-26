<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\SmsNotification;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;

class SmsWebHookController extends Controller
{
    /**
     * @param Request $request
     * @return json
     */
    public function webhookHandler(Request $request){
        // We have access to the request body here
        // So, you can perform any logic with the data
        // In my own case, I will add the delay function
//        dump($request);
        $items =  $request['items'];
//        dump($items);
        foreach ($items as $item) {

            $sms = SmsNotification::where('message_id', $item['internal_id'])->update(['status'=>$item['status']]);

        }


        if($items) {
            return response("", 204);
        } else return response([
            'message' => 'SMS not found'
        ], 400);
    }
}
