<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Company;
use App\Models\Division;
use App\Models\Order;
use App\Models\Rate;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index()
    {

        $user = User::findOrFail(Auth::id());
        $userDivision = UserDivision::where('user_id', $user->id)->first();
        $userCompany = UserCompany::where('user_id', $user->id)->first();
        if ($userDivision) {
            $division = Division::where('id',$userDivision->division_id )->first();

            $rate = Rate::where('id', $division->rate_id)->first();
            $rate_value = $rate->value;
            $sms_value = $division->price_sms;
        } else {
            $rate_value = '';
            $division = '';
            $sms_value = '';
        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';


        return view('order.order', compact('user','company', 'division','rate_value', 'sms_value'));
    }
    public function store(StoreRequest $request)
    {

        $data = $request->validated();
        $orderNumber = (string)rand(1000000, 9999999);
//        $data['birthday'] = date_format($data['birthday'], "Y-m-d");


        $items = json_decode($data['items'], true);
        $sum=0;

        foreach ($items as $key=>$item) {
            $sum +=(int) $item['price'] * ($item['quantity']>0 ? $item['quantity'] : 1);
        }

        $division = Division::where('id', $data['division_id'] )->first();
        if($division) {
            if($division['price_sms']<= 159) {
                $sms=159;
            } else $sms=$division['price_sms'];

            $priceSms = $sms * $data['term_credit'];
            $shopId = $division['shop_id'];
            $showcaseId = $division['show_case_id'];
        } else {
            $priceSms = 0;
            $shopId = '';
            $showcaseId = '';
        }
//        dd($sum+$priceSms);
        $items[] = ['name'=>'СМС-информирование', 'price'=>(string)$priceSms, 'quantity'=>(string)1];
        if($division['find_credit'] == 'on') {
            $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_credit_value'], 'quantity'=>(string)1];
            $sum+=$division['find_credit_value'];
        }
        $data['price_sms'] = $priceSms;

        $data['credit_type'] == 1 ? $promoCode = $data['rate'] : $promoCode=$division->planValue;

        $post = [

        'items'=> $items,
        'sum'=> $sum + $priceSms,
        'demoFlow'=> 'sms',
        'promoCode'=> 'default.'.$promoCode,
        'shopId'=> $shopId,
        'showcaseId'=> $showcaseId,
         "orderNumber"=> $orderNumber,
        'values'=> [
            'contact'=> [
                'fio'=> [
                    'lastName'=> $data['last_name'],
                    'firstName'=> $data['first_name'],
                    'middleName'=> $data['surname'],
                            ],
                'mobilePhone'=> $data['phone'],
                'email'=> $data['email'],
                        ]
        ]
                ];

//        dd(json_encode($post, JSON_UNESCAPED_UNICODE));



        $response = Http::post('https://forma.tinkoff.ru/api/partners/v2/orders/create', $post);
        $data['order_id']=$orderNumber;
        $data['response']= json_encode($response->json(), JSON_UNESCAPED_UNICODE);

        $order = Order::firstOrCreate($data);

// Order::where('id', $order->id)->update(['response'=>json_encode($response->json(), JSON_UNESCAPED_UNICODE)]);
// if(isset($response->json()['id'])) {
//     Order::where('id', $order->id)->update(['order_id'=>$response->json()['id']]);
// }

        if(isset($response->json()['link'])) {
            return redirect($response->json()['link']);
        } else return back()->with('flash_message_error', 'Данные не отправились!');

    }
    public function checkOrders(Order $order) {

        $division = Division::where('id', $order['division_id'])->first();
        $code = base64_encode($division->show_case_id.':pyzPYF7Y5S7Liq@');


        $response =  Http::withHeaders([
            'Authorization' => 'Basic ' . $code,
        ])->get('https://forma.tinkoff.ru/api/partners/v2/orders/'.$order['order_id'].'/info');
        $result = json_decode($response, true);
        if(isset($result['status'])) {
            Order::where('id', $order['id'])->update(['status'=>$result['status']]);
            return back()->with('flash_message_success', $result['status']);
        } else return back()->with('flash_message_success', 'Статус не известен');
//        dd($result);
//        return back()->with('flash_message_success', $result['status']);
    }
}
