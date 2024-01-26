<?php

namespace App\Http\Controllers\Sms;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SmsSettingsRequest;
use App\Models\PayDate;
use App\Models\Setting;
use App\Models\SmsNotification;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index()
    {
//        $date = today();
//        for($i=0; $i<=4; $i++){
//
//            $patDates = new PayDate();
//            $patDates->order_id = 35;
//            $patDates->monthly_payment = 3500;
//            $patDates->date = date_format($date->addMonth(),"Y-m-d");
//            $patDates->save();
//
//        }

        $smsList = SmsNotification::all();
        return view('sms.index', compact('smsList'));
    }

    public function settings()
    {
        $settings = Setting::where('name', 'message')->first();

        return view('sms.settings', compact('settings'));
    }
    public function storeSettings(SmsSettingsRequest $request)
    {
        $data = $request->validated();

        if(!isset($data['first_sms'])) {
            $data['first_sms'] = null;
        }

      $settings =  Setting::where('name', 'message')->first()->update($data);

      if($settings) {
          return back()->with('flash_message_success', 'Данные обновились!');
      } else
       return back()->with('flash_message_error', 'Данные не обновились!');
    }
}
