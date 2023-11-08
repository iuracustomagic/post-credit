<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;

class WebHookController extends Controller
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

       $id = $request['id'];
       $status= $request['status'];
//        dd($id);
      $order= Order::where('order_id', $id)->update(['status'=>$status]);
      if($order) {
          return response("ок", 200);
      } else return response([
          'message' => 'Order not found'
      ], 400);
    }
}
