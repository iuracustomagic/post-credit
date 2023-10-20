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
            $rate_value = $rate->value;
            $sms_value = $division->price_sms;
            $planTerms = DivisionInstallment::where('division_id',$userDivision->division_id)->get();
            $installments = [];
            foreach ($planTerms as $planTerm) {

                $installments[]=Rate::where('id', $planTerm->installment_id)->first();
            }
        } else {
            $rate_value = '';
            $division = '';
            $sms_value = '';
            $installments=[];
        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';


        return view('order.order', compact('user','company', 'division','rate_value', 'sms_value', 'installments'));
    }
    public function store(StoreRequest $request)
    {

        $data = $request->validated();
//        dump($data);
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
        if(isset($data['find_credit'])) {
            if($data['find_credit'] == 'on') {
                $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_credit_value'], 'quantity'=>(string)1];
                $sum+=$division['find_credit_value'];
            }
        }

        if($data['initial_fee'] >0) {
            $items[0]['price']-=$data['initial_fee'];
//            $items[] = ['name'=>'Первоначальный взнос', 'price'=>(string)-$data['initial_fee'], 'quantity'=>(string)1];
            $sum-=$data['initial_fee'];
        }
        $data['price_sms'] = $priceSms;

        if($data['credit_type'] == 1) {
            $promoCode = $data['rate'];
        } else {
            $promoCode=$division->planValue;
            $installment = Rate::where('id', $data['plan_term'])->first();
            $installmentValue = ($sum*$installment['value'])/100;
            $items[0]['price']-=$installmentValue;
//            $items[] = ['name'=>'Процент рассрочки', 'price'=>(string)-$installmentValue, 'quantity'=>(string)1];
            $sum -= $installmentValue;
        }
//        $data['credit_type'] == 1 ? $promoCode = $data['rate'] : $promoCode=$division->planValue;

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

    public function downloadSpecification(Order $order) {
        $items = json_decode($order['items'], true);
        $products =[];
        foreach ($items as $item) {
            $products[]=$item['name'];
        }
        $product =implode(" ", $products);
        isset($order['initial_fee'])? $initialFee=$order['initial_fee']: $initialFee=0;
        $sum = $order['sum_credit']-$order['price_sms']-$initialFee;
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
}
