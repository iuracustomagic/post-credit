<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PayDate;
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
       $docNumber= $request['loan_number'];
//        dd($status);
      $order= Order::where('order_id', $id)->update(['status'=>$status, 'doc_number'=>$docNumber]);
   // save date of payment
        if($status == 'signed') {
            $orderTerm = $request['term'];
            $date = today();
            if($orderTerm == 12) {
                $patDates = new PayDate();
                $patDates->order_id = $id;
                $patDates->monthly_payment = $request['monthly_payment'];

                $date = $date->addDays(45);
                $patDates->date = date_format($date,"Y-m-d");
                $patDates->save();
                for($i=1; $i<$orderTerm; $i++){
                    $patDates = new PayDate();
                    $patDates->order_id = $id;
                    $patDates->monthly_payment = $request['monthly_payment'];
                    $patDates->date = date_format($date->addMonth(),"Y-m-d");
                    $patDates->save();

                }
            } else
            for($i=0; $i<$orderTerm; $i++){
                $patDates = new PayDate();
                $patDates->order_id = $id;
                $patDates->monthly_payment = $request['monthly_payment'];
                $patDates->date = date_format($date->addMonth(),"Y-m-d");
                $patDates->save();

            }
        }



      if($order) {
          return response("ок", 200);
      } else return response([
          'message' => 'Order not found'
      ], 400);
    }
}
