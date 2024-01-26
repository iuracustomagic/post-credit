<?php

namespace App\Http\Controllers\Order;

use App\Classes\ReplacePhone;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Company;
use App\Models\Division;
use App\Models\DivisionInstallment;
use App\Models\Order;
use App\Models\PayDate;
use App\Models\Rate;
use App\Models\Setting;
use App\Models\SmsNotification;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Classes\SendSms;


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
            $rateIfOff = Rate::where('id', $division->rate_if_off)->first();
            if(isset($rate)) {
                if($rate->value == 'default') {
                    $rate_value = 38;
                } else $rate_value = $rate->value;
            } else $rate_value = 1;

            if(isset($rateIfOff)) {
                if($rateIfOff->value == 'default') {
                    $rateIfOffValue = 38;
                } else $rateIfOffValue = $rateIfOff->value;
//                $rateIfOffValue = $rateIfOff->value;
            } else $rateIfOffValue = 1;

            $installment = Rate::where('id', $division->plan_id)->first();
          if(isset($installment)) {
              $installment_value = $installment->value;
          } else $installment_value = 1;

            $sms_value = $division->price_sms;
            $planTerms = DivisionInstallment::where('division_id',$userDivision->division_id)->get();
            $installments = [];
            foreach ($planTerms as $planTerm) {

                $installments[]=Rate::where('id', $planTerm->installment_id)->first();
            }
        } else {
            $rate_value = 1;
            $rateIfOffValue = 1;
            $installment_value = 1;
            $division = '';
            $sms_value = '';
            $installments=[];
        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';


        return view('order.order', compact('user','company', 'division','rate_value', 'rateIfOffValue',  'installment_value', 'sms_value', 'installments'));
    }

    public function createMfo()
    {

        $user = User::findOrFail(Auth::id());
        $userDivision = UserDivision::where('user_id', $user->id)->first();
        $userCompany = UserCompany::where('user_id', $user->id)->first();


        if ($userDivision) {
            $division = Division::where('id',$userDivision->division_id )->first();

            $rate = Rate::where('id', $division->rate_id)->first();
            if(isset($rate)) {
                $rate_value = $rate->value;
            } else $rate_value = 1;

            $installment = Rate::where('id', $division->plan_id)->first();
            if(isset($installment)) {
                $installment_value = $installment->value;
            } else $installment_value = 1;
            if(isset($division->price_sms_mfo)) {
                $sms_value = $division->price_sms_mfo;
            } else $sms_value =0;

//            $planTerms = DivisionInstallment::where('division_id',$userDivision->division_id)->get();
//            $installments = [];
//            foreach ($planTerms as $planTerm) {
//
//                $installments[]=Rate::where('id', $planTerm->installment_id)->first();
//            }
        } else {
            $rate_value = 1;
            $installment_value = 1;
            $division = '';
            $sms_value = 0;
//            $installments=[];
        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';


        return view('order.orderMfo', compact('user','company', 'division','rate_value', 'installment_value', 'sms_value'));
    }

    public function store(StoreRequest $request)
    {

        $data = $request->validated();
//        dump($data);
        $orderNumber = (string)rand(1000000, 9999999);
//        $data['birthday'] = date_format($data['birthday'], "Y-m-d");


        $items = json_decode($data['items'], true);
        $sum=0;
        $sms=0;
        foreach ($items as $key=>$item) {
            $sum +=(int) $item['price'] * ($item['quantity']>0 ? $item['quantity'] : 1);
        }
        $transfer_sum = $sum;
        $division = Division::where('id', $data['division_id'] )->first();
        if($division) {
            if($division['price_sms']>0 && $division['price_sms']<= 159) {
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

        if ($sms > 0){
            $items[] = ['name'=>'СМС-информирование', 'price'=>(string)$priceSms, 'quantity'=>(string)1];
        }


        if($data['initial_fee'] >0) {
            $items[0]['price']-=$data['initial_fee'];
            $transfer_sum-=$data['initial_fee'];
            $sum-=$data['initial_fee'];
        }

        if($data['credit_type'] == 1) {
            $promoCode = $data['rate'];
        } else {
            $promoCode=$division->planValue;
            $installment = Rate::where('id', $data['plan_term'])->first();
            $installmentValue = ($sum*$installment['value'])/100;
            $transferValue = ($transfer_sum*$installment['transfer_value'])/100;
            $items[0]['price']-=round($installmentValue);
            $sum -= intval(round($installmentValue));
            $transfer_sum -= intval(round($transferValue));
        }


        if(isset($data['find_credit'])) {
            if($data['find_credit'] == 'on') {
                $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_credit_value'], 'quantity'=>(string)1];
                $sum+=$division['find_credit_value'];
            }
        } elseif($division['hide_find_credit'] == 'on' && isset($division['find_credit_value'])) {
            $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_credit_value'], 'quantity'=>(string)1];
            $sum+=$division['find_credit_value'];
            $data['find_credit'] = 'on';
        } else {
            $rateIfOff =  Rate::where('id', $division['rate_if_off'])->first();
            if(isset($rateIfOff)) {
                $promoCode = $rateIfOff['value'];
                $data['rate'] = $rateIfOff['value'];
            }

        }


        $data['price_sms'] = $priceSms;
        $data['transfer_sum'] = $transfer_sum;
        $data['sum_credit'] = $sum + $priceSms;

//        dump($data);

        $post = [
        'items'=> $items,
        'sum'=> $sum + $priceSms,
        'demoFlow'=> 'sms',
        'promoCode'=> ($promoCode == 'default') ? 'default' : 'default.'.$promoCode,
        'shopId'=> $shopId,
        'showcaseId'=> $showcaseId,
         "orderNumber"=> $orderNumber,
//         "webhookURL"=> 'https://xn--b1aeckdhc1bmragcd.xn--p1ai/webhook-order',
//         "successURL"=> 'https://xn--b1aeckdhc1bmragcd.xn--p1ai',
//         "failURL"=> 'https://xn--b1aeckdhc1bmragcd.xn--p1ai',
//         "returnURL"=> 'https://xn--b1aeckdhc1bmragcd.xn--p1ai',
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


//if($division['shop_id'] == '4dcaa2b7-5cf1-4be2-b05b-3406ca7273b6' && $division['show_case_id'] == 'a3b40311-2483-46c7-995d-7f96a0197968') {
//    $post['webhookURL'] = 'https://xn--b1aeckdhc1bmragcd.xn--p1ai/webhook-order/OOO987654356667romashka';
//}
//       dd(json_encode($post, JSON_UNESCAPED_UNICODE));

        $response = Http::post('https://forma.tinkoff.ru/api/partners/v2/orders/create', $post);
        $data['order_id']=$orderNumber;
        $data['response']= json_encode($response->json(), JSON_UNESCAPED_UNICODE);

        $order = Order::firstOrCreate($data);

// Order::where('id', $order->id)->update(['response'=>json_encode($response->json(), JSON_UNESCAPED_UNICODE)]);
// if(isset($response->json()['id'])) {
//     Order::where('id', $order->id)->update(['order_id'=>$response->json()['id']]);
// }

        //.', ссылка - '.$response->json()['link']


            $link = $response->json()['link'];
        if(isset($link)) {

            $settings = Setting::where('name', 'message')->first();
            if($settings['first_sms'] == 'on'){
                $userPhone = $order['phone'];
                $code = (string)rand(10000, 99999);

                $naming = env('MTS_NAMING');
                $smsText = $settings['message_accept'].', код подтверждения -'. $code. ', ссылка на оферту - https://vk.cc/cu6z0q';
                $phone = new ReplacePhone();
                $userPhone = $phone->replace($userPhone);

                $client = new SendSms("https://omnichannel.mts.ru/http-api/v1/", env('MTS_LOGIN'), env('MTS_PASSWORD'));

                $id = $client->sendSms($naming, $smsText, $userPhone);

//                dump($id);
                if ($id != "0") {
                    $smsData = [
                        'user'=>$order['first_name'].' '.$order['last_name'].' '.$order['surname'],
                        'phone'=>$order['phone'],
                        'message_text'=>$smsText,
                        'status'=> 'pending',
                        'message_id'=> $id,
                        'code_success'=> $code,

                    ];
                    $smsRequest = SmsNotification::firstOrCreate($smsData);
                    // получение статуса по id сообщения
//                    $status = $client->getSmsInfo([$id]);
//                    dump($status);
                }

//                dd('stop');
            } else {
                return redirect($response->json()['link']);
            }

            return view('order.acceptMfo', compact('link','id', 'code', 'userPhone'));
        } else {
            $order->update(['status'=>'failed']);
            return back()->with('flash_message_error', 'Данные не отправились!');
        }

    }

    public function sendSmsCode(Request $request) {
        $link = $request->input('link');
        $userPhone = $request->input('userPhone');

        if(isset($_POST['sendSms'])) {

            $code = (string)rand(10000, 99999);
            $settings = Setting::where('name', 'message')->first();
            $naming = env('MTS_NAMING');
            $smsText = $settings['message_accept'].', код подтверждения -'. $code. ', ссылка на оферту - https://vk.cc/cu6z0q';
            $client = new SendSms("https://omnichannel.mts.ru/http-api/v1/", env('MTS_LOGIN'), env('MTS_PASSWORD'));

            $id = $client->sendSms($naming, $smsText, $userPhone);

//                dump($id);
            if ($id != "0") {
                return view('order.acceptMfo', compact('link', 'code', 'userPhone'));
            } else
            return back()->with('flash_message_error', 'Сообщение не отправлено!');
//            dd($result);
        } elseif(isset($_POST['sendData'])) {


            if($request->input('code') == $request->input('sms_code')) {
                return redirect($link);
            } else return view('order.acceptMfo', compact('link',  'userPhone'));

        }


    }


    public function checkOrders(Order $order) {

        $division = Division::where('id', $order['division_id'])->first();
        $code = base64_encode($division->show_case_id.':pyzPYF7Y5S7Liq@');

        // sms sending
        $phone = '79313023149';
        $naming = 'MTSM_Test';
        $smsText = 'Vasha zayavka byla odobrena';
        function generateRandomString($length = 10) {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        }
        $messageId = generateRandomString(20);
        $mtsStatus = ["events_info"=> [
            [
                "events_info"=> [
            [
            "channel"=> 4,
            "destination"=> "79870061042",
            "event_at"=> "2021-05-18T13:45:01.000+03:00",
            "internal_errors"=> null,
            "internal_id"=> "c128187b-145f-4b86-b47f-8e99cf513a59",
            "message_id"=> "10345678-f123-4694-91d7-233df79f65445",
            "naming"=> "viber_naming",
            "received_at"=> "2021-05-18T13:45:00.000+03:00",
            "status"=> 200,
            "total_parts"=> 1
            ],
            [
                "channel"=> 4,
            "destination"=> "79870061042",
            "event_at"=> "2021-05-18T13:45:05.000+03:00",
            "internal_errors"=> null,
            "internal_id"=> "c128187b-145f-4b86-b47f-8e99cf513a59",
            "message_id"=> "10345678-f123-4694-91d7-233df79f65445",
            "naming"=> "viber_naming",
            "received_at"=> "2021-05-18T13:45:00.000+03:00",
            "status"=> 300,
            "total_parts"=> 1
            ]
            ],
            "key"=> "c128187b-145f-4b86-b47f-8e99cf513a59"
            ]
             ]];
//        $ar = $mtsStatus['events_info'][0]['events_info'];
//dd(end($ar)['status']);
        $messageData = [
            'messages '=> [
                [
                    "content" =>   ["short_text"=> $smsText],
                    'to'=> [
                        [
                            "msisdn" => $phone,
                            "message_id"=>$messageId
                        ]
                    ]
                ],
                "options" => [
                    "class" => 1,
                    "from" => [
                        "sms_address" => $naming,
                    ],
                ]

            ]
        ];
        $smsData = [
            'user'=>$order['first_name'].' '.$order['last_name'].' '.$order['surname'],
            'phone'=>$order['phone'],
            'message_text'=>$smsText,
            'status'=> 'pending',
            'message_id'=> $messageId,

        ];
        //      $mtsResponse =  Http::withBasicAuth(env('MTS_LOGIN'), env('MTS_PASSWORD'))->post('https://omnichannel.mts.ru/http-api/v1/messages', $messageData);


//        $receivedSms = false;
//      if($mtsResponse->status() == 200) {
//          $data= [
//              "msg_ids"=>[
//                  $messageId
//              ]
//          ];
//            while($receivedSms) {
//                $mtsStatus =  Http::withBasicAuth(env('MTS_LOGIN'), env('MTS_PASSWORD'))
//                    ->post('https://omnichannel.mts.ru/http-api/v1/messages/info ', $data);
//
//                $ar = $mtsStatus['events_info'][0]['events_info'];
//                $status = end($ar)['status'];
//                if($status == 300 || $status == 302){
//                    $smsRequest->update(['status'=>'sent']);
//                    $receivedSms =true;
//                } else if($status == 201 || $status == 301) {
//                    $smsRequest->update(['status'=>'failed']);
//                    $receivedSms =true;
//                }
//                    else {
//
//                    sleep(10);
//
//                }
//            }
//      }
        // end sms sending


        $response =  Http::withHeaders([
            'Authorization' => 'Basic ' . $code,
        ])->get('https://forma.tinkoff.ru/api/partners/v2/orders/'.$order['order_id'].'/info');
        $result = json_decode($response, true);


//        dd($result);
        if(isset($result['status'])) {
            Order::where('id', $order['id'])->update(['status'=>$result['status']]);
            switch ($result['status']) {
                case 'inprogress':
                    $status = 'В процессе';
                    break;
                case 'new':
                    $status = 'Новая';
                    break;
                case 'approved':
                    $status = 'Одобрена';
                    break;
                case 'signed':
                    $status = 'Подписана';
                    break;
                case 'canceled':
                    $status = 'Отменена';
                    break;
                case 'rejected':
                    $status = 'Отказ';
                    break;
                default:
                    $status = 'Не известен';

            }

            return back()->with('flash_message_success', $status);
        } else return back()->with('flash_message_success', $result);
//        dd($result);
//        return back()->with('flash_message_success', $result['status']);
    }

    public function downloadSpecification(Order $order) {
        $items = json_decode($order['items'], true);
        $products =[];
        foreach ($items as $item) {
            $products[]=$item['name'];
        }
        $product =implode(" ", $products);
        isset($order['initial_fee'])? $initialFee=$order['initial_fee']: $initialFee=0;
//        $sum = $order['sum_credit']-$order['price_sms']-$initialFee;
        $sum = $order['transfer_sum'];
        $templateProcessor = new TemplateProcessor('word-template/report.docx');
        $templateProcessor->setValue('date', $order['created_at']);
        $templateProcessor->setValue('first_name', $order['first_name']);
        $templateProcessor->setValue('last_name', $order['last_name']);
        $templateProcessor->setValue('surname', $order['surname']);
        $templateProcessor->setValue('sum', $sum);
        $templateProcessor->setValue('initial_fee', $initialFee);
        $templateProcessor->setValue('product', $product);
        $templateProcessor->setValue('division', $order->divisionName);

        $fileName = 'Спецификация.docx';
        $fileStorage = public_path('word-template/' . $fileName);
        $templateProcessor->saveAs($fileStorage);

        //Load export library
        $domPdfPath = base_path( 'vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

        //load generated file
        $phpWord = IOFactory::load($fileStorage);
        $phpWord->setDefaultFontName('DejaVu Sans');

        $xmlWriter = IOFactory::createWriter($phpWord , 'PDF');
        if ( file_exists($fileStorage) ) {
            unlink($fileStorage);
        }

        //save generated File
        $pdfLocation = public_path('Спецификация.pdf');
        $xmlWriter->save($pdfLocation, true);

        return response()->download($pdfLocation)->deleteFileAfterSend(true);
    }
    public function continueOrder(Order $order)  {

    $response = json_decode($order['response']);

//    dd($link);
        if(isset($response->link)) {
            return redirect($response->link);
        } else return back()->with('flash_message_error', 'Заявка не найдена');

    }

    public function cancelOrder(Order $order)  {
        $division = Division::where('id', $order['division_id'])->first();
        $code = base64_encode($division->show_case_id.':pyzPYF7Y5S7Liq@');


        $response =  Http::withHeaders([
            'Authorization' => 'Basic ' . $code,
        ])->get('https://forma.tinkoff.ru/api/partners/v2/orders/'.$order['order_id'].'/cancel');
        $result = json_decode($response, true);
        if($result) {
            if(isset($result['status'])) {
                Order::where('id', $order['id'])->update(['status'=>$result['status']]);
                return back()->with('flash_message_success', $result['status']);
            } else return back()->with('flash_message_success', 'Статус не известен');
        } else return back()->with('flash_message_error', 'Заявка не найдена');

    }

    public function checkMfo(Request $request){
        $order_id = $request["order_id"];
        $login = env('MFO_LOGIN');
        $password = env('MFO_PASSWORD');
        $token = md5($login . md5($password) . $order_id);
//dd($token);
// Ввывод строкой. Разделитель \n
        if ($token != $request["token"])
        {
            echo "RESULT:-1\nDESCR:ошибка определения источника запроса";
            exit;
        }

// Есть ли возможность отгрузить заказ
// Процедура GetOrderApprove должна найти заказ по order_id, и сравнить сумму заказа с переданной суммой

$order = Order::where('order_id', $request["order_id"])->where('sum_credit', $request["sum"])->first();

        if ( $request["state"] == 0 )
        {
            if (isset($order))
            {
                // DESCR: может иметь любое присвоенное Вами значение, поскольку при данном статусе клиенту не выводится.
                echo"RESULT:1\nDESCR:актуален";
            }
            else
            {
                // В данном случае значение DESCR будет показано клиенту
                echo"RESULT:0\nDESCR:Ваш заказ был отменен менеджером";
            }
        }

// Клиент оплатил
        if ( $request["state"] == 1 )
        {
            if (isset($order))
            {
                // Устанавливаем статус заказа «клиент оплатил» и фиксируем application_id
                $order->status = 'signed';
                $order->response = $_POST["application_id"];
                $order->save();
//                SaveOrderState($_POST["order_id"], $_POST["application_id"], "оплатил");
                // DESCR: может иметь любое присвоенное Вами значение, поскольку при данном статусе клиенту не выводится).
                echo "RESULT:1\nDESCR:статус оплатил";
            }
            else
            {
                // В данном случае значение DESCR будет показано клиенту
                echo "RESULT:0\nDESCR:Ваш заказ был аннулирован по сроку давности";
            }
        }

// Клиент отказался от оплаты заказа
        if ( $request["state"] == -1 )
        {
            if (isset($order))
            {
                // Устанавливаем статус заказа «клиент отказался» и фиксируем application_id
//                SaveOrderState($_POST["order_id"], $_POST["application_id"], "отказался");
                $order->status = 'rejected';
                $order->response = $request["application_id"];
                $order->save();
                // DESCR: может иметь любое присвоенное Вами значение, поскольку при данном статусе клиенту не выводится).
                echo "RESULT:1\nDESCR:отказ принят";
            }
        }

    }
    public function getToken(Request $request) {
        $order_id = $request["order_id"];
        $login = env('MFO_LOGIN');
        $password = env('MFO_PASSWORD');
        $token = md5($login . md5($password) . $order_id);
        echo $token;
    }

    public function copyOrder(Order $order) {
        $user = User::findOrFail(Auth::id());
        $userDivision = UserDivision::where('user_id', $user->id)->first();
        $userCompany = UserCompany::where('user_id', $user->id)->first();


        if ($userDivision) {
            $division = Division::where('id',$userDivision->division_id )->first();

            $rate = Rate::where('id', $division->rate_id)->first();
            if(isset($rate)) {
                $rate_value = $rate->value;
            } else $rate_value = 1;

            $installment = Rate::where('id', $division->plan_id)->first();
            if(isset($installment)) {
                $installment_value = $installment->value;
            } else $installment_value = 1;
            if(isset($division->price_sms_mfo)) {
                $sms_value = $division->price_sms_mfo;
            } else $sms_value =0;


        } else {
            $rate_value = 1;
            $installment_value = 1;
            $division = '';
            $sms_value = 0;
//            $installments=[];
        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';

        $copiedData=[
            'first_name'=>$order['first_name'],
            'last_name'=>$order['last_name'],
            'surname'=>$order['surname'],
            'birthday'=>$order['birthday'],
            'phone'=>$order['phone'],
        ];

        return view('order.orderMfo', compact('copiedData','user','company', 'division','rate_value', 'installment_value', 'sms_value'));
    }
    public function sentSms() {
        $threeDaysLater = date ('Y-m-d', strtotime ('+3 day'));

        $payDates = PayDate::where('date', $threeDaysLater)->get();

        if(isset($payDates)) {
            foreach ($payDates as $payDate) {
                $order = Order::where('order_id', $payDate->order_id)->first();

//
//                $smsData = [
//                    'user' => $order->first_name . ' ' . $order->last_name . ' ' . $order->surname,
//                    'phone' => $order->phone,
//                    'message_text' => 'Ваш следующий платеж ' . $threeDaysLater . ' в размере ' . $payDate->monthly_payment. ' руб.',
//                    'status' => 'pending',
//                    'message_id' => '2445574452588',
//
//                ];
//                $smsRequest = SmsNotification::firstOrCreate($smsData);

                $naming = env('MTS_NAMING');
                $smsText = 'Ваш следующий платеж ' . $threeDaysLater . ' в размере ' . $payDate->monthly_payment. ' руб.';
                $phone = new ReplacePhone();
                $userPhone = $phone->replace($order->phone);

                $client = new SendSms("https://omnichannel.mts.ru/http-api/v1/", env('MTS_LOGIN'), env('MTS_PASSWORD'));

                $id = $client->sendSms($naming, $smsText, $userPhone);

//                dump($id);
                if ($id != "0") {
                    $smsData = [
                        'user' => $order->first_name . ' ' . $order->last_name . ' ' . $order->surname,
                        'phone' => $order->phone,
                        'message_text' => 'Ваш следующий платеж ' . $threeDaysLater . ' в размере ' . $payDate->monthly_payment,
                        'status' => 'pending',
                        'message_id' => '2445574452588',

                    ];
                    $smsRequest = SmsNotification::firstOrCreate($smsData);
                }
            }
        }


        if($payDates) {
            return response('ok', 200);
        } else return response("not found", 400);
    }
}
