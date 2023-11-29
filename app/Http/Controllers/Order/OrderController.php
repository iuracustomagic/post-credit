<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Company;
use App\Models\Division;
use App\Models\DivisionInstallment;
use App\Models\Order;
use App\Models\Rate;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\TemplateProcessor;

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
            if(isset($rate)) {
                $rate_value = $rate->value;
            } else $rate_value = 1;

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
            $installment_value = 1;
            $division = '';
            $sms_value = '';
            $installments=[];
        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';


        return view('order.order', compact('user','company', 'division','rate_value', 'installment_value', 'sms_value', 'installments'));
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

            $planTerms = DivisionInstallment::where('division_id',$userDivision->division_id)->get();
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
        }


        $data['price_sms'] = $priceSms;
        $data['transfer_sum'] = $transfer_sum;
        $data['sum_credit'] = $sum + $priceSms;

//        dump($data);

        $post = [
        'items'=> $items,
        'sum'=> $sum + $priceSms,
        'demoFlow'=> 'sms',
        'promoCode'=> 'default.'.$promoCode,
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

        if(isset($response->json()['link'])) {
            return redirect($response->json()['link']);
        } else {
            $order->update(['status'=>'failed']);
            return back()->with('flash_message_error', 'Данные не отправились!');
        }

    }

    public function storeMfo(StoreRequest $request) {
        $data = $request->validated();
//        dump($data);
        $order_id = rand(10000000, 99999999);
//        $data['birthday'] = date_format($data['birthday'], "Y-m-d");


        $items = json_decode($data['items'], true);
        $sms=0;
        $sum=0;
        $goods = [];
        foreach ($items as $key=> $item) {
            $good = [];
            $good['Name'] = $item['name'];
            $good['Price'] = $item['price'];
            $good['Count'] = $item['quantity'];
            $goods[]= $good;
            $sum +=(int) $item['price'] * ($item['quantity']>0 ? $item['quantity'] : 1);
        }

        $transfer_sum = $sum;
        $division = Division::where('id', $data['division_id'] )->first();
        if($division) {
            if($division['price_sms_mfo'] > 0 && $division['price_sms_mfo']<= 159) {
                $sms=159;
            } else $sms=$division['price_sms_mfo'];

            $priceSms = $sms * $data['term_credit'];

        } else {
            $priceSms = 0;

        }

            if($sms > 0) {
                $items[] = ['name'=>'СМС-информирование', 'price'=>(string)$priceSms, 'quantity'=>(string)1];
                $goods[] = ['Name'=>'СМС-информирование', 'Price'=>(string)$priceSms, 'Count'=>1];
            }


        if($data['initial_fee'] >0) {
            $items[0]['price']-=$data['initial_fee'];
            $goods[0]['Price']-=$data['initial_fee'];
            $transfer_sum-=$data['initial_fee'];
            $sum-=$data['initial_fee'];
        }
        if(isset($data['find_credit'])) {
            if($data['find_credit'] == 'on') {
                $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_mfo_value'], 'quantity'=>(string)1];
                $goods[] = ['Name'=>'Подбор кредита', 'Price'=>(string)$division['find_mfo_value'], 'Count'=>1];
                $sum+=$division['find_mfo_value'];
            }
        } elseif($division['hide_find_mfo'] == 'on' && isset($division['find_mfo_value'])) {
            $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_mfo_value'], 'quantity'=>(string)1];
            $goods[] = ['Name'=>'Подбор кредита', 'Price'=>(string)$division['find_mfo_value'], 'Count'=>1];
            $sum+=$division['find_mfo_value'];
            $data['find_mfo'] = 'on';
        }

        $login = "test"; // Логин Вашей организации в системе paylate
        $password = "test"; // Пароль Вашей организации в системе paylate
//        $order_id = "235"; // Идентификатор заказа в вашей системе
        $token = md5($login . md5($password) . $order_id); // Сформированный токен

        $post = [
//            'URL'=> 'https://paylate.ru',
            'client_id'=> '1702082013',
            'order_id'=> $order_id,

            'sum'=> $sum + $priceSms,
            'goods'=>urlencode(json_encode($goods, JSON_UNESCAPED_UNICODE)),
//            'goods'=>json_encode($goods, JSON_UNESCAPED_UNICODE) ,
            'token'=> $token,
            'action'=> 'by_partner',
            'pregen'=> 1,
            'result_url'=> 'https://xn--b1aeckdhc1bmragcd.xn--p1ai',
            'fio' =>$data['last_name'],
            'fio1' =>$data['first_name'],
            'fio2' =>$data['surname']
        ];

        $data['order_id']=$order_id;
        $data['price_sms'] = $priceSms;
        $data['transfer_sum'] = $transfer_sum;
        $data['sum_credit'] = $sum + $priceSms;
//dump($data);
//dd(json_encode($post, JSON_UNESCAPED_UNICODE));

        $order = Order::firstOrCreate($data);



        return view('components.formMfo', compact('post'));

//        $response = Http::post('https://paylate.ru/bypartner', $post);
//        dd($response);
//        if(isset($response)) {
//            return $response ;
//        }else {
//            return back()->with('flash_message_error', 'Данные не отправились!');
//        }




// Order::where('id', $order->id)->update(['response'=>json_encode($response->json(), JSON_UNESCAPED_UNICODE)]);
// if(isset($response->json()['id'])) {
//     Order::where('id', $order->id)->update(['order_id'=>$response->json()['id']]);
// }
//if(!$response){
//                $order->update(['status'=>'failed']);
//            return back()->with('flash_message_error', 'Данные не отправились!');
//}
//        if(isset($response)) {
//            return $response ;
//        } else {
//            $order->update(['status'=>'failed']);
//            return back()->with('flash_message_error', 'Данные не отправились!');
//        }
    }


    public function checkOrders(Order $order) {

        $division = Division::where('id', $order['division_id'])->first();
        $code = base64_encode($division->show_case_id.':pyzPYF7Y5S7Liq@');


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
        $login = "test";
        $password = "test";
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
        $login = "test";
        $password = "test";
        $token = md5($login . md5($password) . $order_id);
        echo $token;
    }
}
