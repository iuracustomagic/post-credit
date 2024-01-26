<?php

namespace App\Http\Controllers\Order;

use App\Classes\Helper;
use App\Classes\ReplacePhone;
use App\Http\Controllers\Controller;
use App\Models\AdditionalOrderData;
use App\Models\Company;
use App\Models\Division;
use App\Models\MfoClient;
use App\Models\MfoOrder;

use App\Models\Rate;
use App\Models\Segment;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserDivision;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

class OrderMfoController extends Controller
{
    public function index()
    {

        $user = User::findOrFail(Auth::id());
        $userDivision = UserDivision::where('user_id', $user->id)->first();
        $userCompany = UserCompany::where('user_id', $user->id)->first();


        if ($userDivision) {
            $division = Division::where('id',$userDivision->division_id )->first();


            if(isset($division->price_sms_mfo)) {
                $sms_value = $division->price_sms_mfo;
            } else $sms_value =0;


        } else {
            $division = '';
            $sms_value = 0;

        }
        if($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
        } else  $company = '';

        $rates = Rate::where('type', 'mfo')->get();
        return view('order.orderMfo', compact('user','company', 'division', 'rates', 'sms_value'));
    }


    public function store(Request $request) {

        if(isset($_POST['sendSms'])) {

            $phoneReq = $request->input('phoneSms');
            $phone = new ReplacePhone();
            $userPhone = $phone->replace($phoneReq);

            $response =  Http::withHeaders([
                'Authorization'=> 'Token '.env('MFO_TOKEN'),
                'Content-Type'=> 'application/x-www-form-urlencoded'
            ])->post('https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/sopdCode/send', ['mobilePhoneNumber'=>$userPhone]);
            $result = json_decode($response, true);


            if(isset($result['triesLeft']) && isset($result['dateSent'])) {
                return back()->withInput(array('triesLeft' => $result['triesLeft'], 'dateSent' => $result['dateSent']));
            }
            if($result['reason'] =='Too Many Codes' ) {
                return back()->with('flash_message_error', 'Слишком много попыток отправки на этот номер!');
            }
            return back()->with('flash_message_error', 'Сообщение не отправлено!');

        } elseif(isset($_POST['sendData'])) {
            $data = $request->validate([
                'status' => 'required|string',
                'rate' => 'required',
                'items' => 'required|string',
                'first_name' => 'required|min:2|string',
                'last_name' => 'required|min:2|string',
                'surname' => 'required|min:2|string',
                'salesman_id' => 'required|integer',
                'birthday' => 'nullable',
                'phone' => 'required|min:3|string',
                'email' => 'required|string',
                'term_credit' => 'nullable|integer',
                'sum_credit' => 'required',
                'company_id' => 'required',
                'division_id' => 'required',
                'date_sent' => 'nullable',
                'plan_term' => 'nullable|integer',
                'initial_fee' => 'nullable|integer',
                'find_credit' => 'nullable',
                'transfer_sum' => 'nullable',
                'password_id' => 'required|min:10|string',
                'password_code' => 'required|string',
                'password_date' => 'required',
                'password_by' => 'required|string',
                'address_index' => 'nullable|string',
                'address_region' => 'nullable|string',
                'address_city' => 'nullable|string',
                'address_street' => 'nullable|string',
                'address_house' => 'nullable|string',
                'address_block' => 'nullable|string',
                'address_flat' => 'nullable|string',
                'residence' => 'required|integer',
                'doc_set' => 'required|integer',

                'sms_code' => 'required|string',
                'birth_place' => 'required|string',
                'income_amount' => 'required|integer',
            ]);
//        dump($data);
            $order_id = rand(10000000, 99999999);

            $phone = new ReplacePhone();
            $userPhone = $phone->replace($data['phone']);
            $dateFromBirthday = strtotime($data['birthday']);
            $dateFromPassword = strtotime($data['password_date']);
            $birthdayDate = date(date('Y-m-d H:i:s', $dateFromBirthday));
            $passwordDate = date(date('Y-m-d H:i:s', $dateFromPassword));

            $division = Division::where('id', $data['division_id'] )->first();
            if($division) {
                if($division['price_sms_mfo'] > 0 && $division['price_sms_mfo']<= 159) {
                    $sms=159;
                } else $sms=$division['price_sms_mfo'];

                $priceSms = $sms;
                $segment = Segment::where('id', $division['segment_id'])->first();

                if(isset($segment)) {
                    $category =$segment['name'];
                    $shopId =$segment['shop_id'];
                } else {
                    $category = 'Мобильные телефоны';
                    $shopId = 64108;
                }

            } else {
                $priceSms = 0;
                $category = 'Мобильные телефоны';
                $shopId = 64108;
            }

            $items = json_decode($data['items'], true);
            $sms=0;
            $sum=0;
            $goods = [];
            foreach ($items as $key=> $item) {
                $good = [];
                $good['name'] = $item['name'];
                $good['category'] = $category;
                $good['price']['currency'] = 'RUB';
                $good['price']['value'] =round($item['price']) ;
                $good['count'] = $item['quantity'];
                $goods[]= $good;
                $sum +=(int) $item['price'] * ($item['quantity']>0 ? $item['quantity'] : 1);
            }

            $transfer_sum = $sum;


            if($sms > 0) {
                $items[] = ['name'=>'СМС-информирование', 'price'=>(string)$priceSms, 'quantity'=>(string)1];
                $price = ['currency'=>'RUB', 'value'=>round($priceSms, 2)];
                $goods[] = ['name'=>'СМС-информирование', 'price'=>$price, 'count'=>1];
            }



            if(isset($data['date_sent'])) {
                $dateSent = $data['date_sent'];
            } else $dateSent = date('Y-m-d H:i:s');
            if(isset($data['find_credit'])) {
                if($data['find_credit'] == 'on') {
                    $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_mfo_value'], 'quantity'=>(string)1];
                    $price = ['currency'=>'RUB', 'value'=>round($division['find_mfo_value'], 2)];
                    $goods[] = ['name'=>'Подбор кредита', 'price'=>$price, 'count'=>1];
                    $sum+=$division['find_mfo_value'];
                    $data['find_mfo'] = 'on';
                }
            } elseif($division['hide_find_mfo'] == 'on' && isset($division['find_mfo_value'])) {
                $items[] = ['name'=>'Подбор кредита', 'price'=>(string)$division['find_mfo_value'], 'quantity'=>(string)1];
                $price = ['currency'=>'RUB', 'value'=>round($division['find_mfo_value'], 2)];
                $goods[] = ['name'=>'Подбор кредита', 'price'=>$price, 'count'=>1];
                $sum+=$division['find_mfo_value'];
                $data['find_mfo'] = 'on';
            }
            $rate = Rate::where('type', 'mfo')->where('value', $data['rate'])->first();

            if($rate['term'] == 12) {
                $rateId = env('MFO_RATE_ID_12');

            } else {
                $rateId = env('MFO_RATE_ID_18');

            }

            $post = [
                'rateId'=> (int)$rateId,
                'shopId'=> $shopId,

                'order'=>[
                    'id'=>(string)$order_id,
                    'amount'=>[
                        'currency'=>'RUB',
                        'value'=>$sum + $priceSms
                    ],
                    'initialFee'=>[
                        'currency'=>'RUB',
                        'value'=>0.0
                    ],
                    "basketItems"=> $goods,
                    "earlyRepayment"=> [
                        "Currency"=> "RUB",
                        "Value"=> 0.0
                    ]

                ],
                "customerConfirmationDetails"=> [
                    "phoneNumber"=> $userPhone,
                    "ip"=> $request->ip(),
                    "timestamp"=> $dateSent,
//                    "deviceFingerprint"=> "Mozilla/5.0 (iPad; CPU OS 7_0_3 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) CriOS/31.0.1650.18 Mobile/11B511 Safari/8536.25*",
                    "deviceFingerprint"=>"iPad; CPU OS 7_0_3 like Mac OS X",
                    "sendCode"=> 1,
                    "code"=> $data['sms_code']
                ],
                "customer"=>[
                    "partnerUserId"=> null,
                    "personalData"=> [
                        "firstName"=> $data['first_name'],
                        "lastName"=> $data['last_name'],
                        "email"=> $data['email'],
                        "middleName"=> $data['surname'],
                        "registrationAddress"=> [
                            "index"=> $data['address_index'],
                            "region"=> $data['address_region'],
                            "city"=> $data['address_city'],
                            "street"=> $data['address_street'],
                            "house"=> $data['address_house'],
                            "block"=> $data['address_block'],
                            "flat"=> $data['address_flat']
                        ],
                        "residenceAddress"=> null,
                        "document"=> [
                            "type"=> 1,
                            "number"=> $data['password_id'],
                            "issueDate"=> $passwordDate,
                            "issuer"=> $data['password_by'],
                            "issuerCode"=> $data['password_code']
                        ],
                        "phoneNumber"=> $userPhone,
                        "additionalPhoneNumber"=> null,
                        "dateOfbirth"=> $birthdayDate,
                        "birthPlace"=> $data['birth_place'],
                        "incomeAmount"=> [
                            "currency"=> "RUB",
                            "value"=>(int)$data['income_amount']
                        ]
                    ],

                ],
            ];

            $status = $data['status'];
            $rate = $data['rate'];
            $salesmanId = $data['salesman_id'];
            $companyId = $data['company_id'];
            $divisionId = $data['division_id'];

            $term_credit = $data['term_credit'];
            $items = $data['items'];
            if(isset($data['find_mfo'])) {
                $findMfo = $data['find_mfo'];
            }else $findMfo ='';

            $clientData = [
                'first_name'=>$data['first_name'],
                'last_name'=>$data['last_name'],
                'surname'=>$data['surname'],
                'birthday'=>$data['birthday'],
                'phone'=>$data['phone'],
                'password_id'=>$data['password_id'],
                'password_code'=>$data['password_code'],
                'password_date'=>$data['password_date'],
                'password_by'=>$data['password_by'],
                'address_index'=>$data['address_index'],
                'address_region'=>$data['address_region'],
                'address_city'=>$data['address_city'],
                'address_street'=>$data['address_street'],
                'address_house'=>$data['address_house'],
                'address_block'=>$data['address_block'],
                'address_flat'=>$data['address_flat'],
                'residence'=>$data['residence'],
                'birth_place'=>$data['birth_place'],
                'doc_set'=>$data['doc_set'],
                'income_amount'=>$data['income_amount'],
            ];

        $client = MfoClient::firstOrCreate($clientData);


            $orderData = [
                'status'=>$status,
                'order_id'=>$order_id,
                'rate'=>$rate,
                'salesman_id'=>$salesmanId,
                'company_id'=>$companyId,
                'division_id'=>$divisionId,
                'customer_id'=>$client['id'],
//                'customer_id'=>5,
                'sum_credit'=>$sum + $priceSms,
                'transfer_sum'=>$transfer_sum,
                'find_mfo'=>$findMfo,
                'term_credit'=>$term_credit,
                'items'=>$items,
                'price_sms'=>$priceSms,
            ];


        $order = MfoOrder::firstOrCreate($orderData);
           // dd(json_encode($post, JSON_UNESCAPED_UNICODE));

            $data = json_encode($post, JSON_UNESCAPED_UNICODE );

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/create',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                    'Authorization: Token 306fda31bab65655f3ef3f9cd87f2d95',
                    'Accept-Charset: utf-8'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
//            echo $response;
//
//            $response =  Http::withHeaders([
//                'Authorization'=> 'Token '.env('MFO_TOKEN'),
//                'Content-Type'=> 'application/x-www-form-urlencoded; charset=utf-8'
////                'Charset' => 'utf-8'
//            ])->post('https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/create', json_encode($post, JSON_UNESCAPED_UNICODE));

            $result = json_decode($response, true);

            if(isset($result['partnerUserId'])) {
                $client->partner_user_id = $result['partnerUserId'];
                $client->save();
            }

            if(isset($result['reason'])) {
                $reason = $result['reason'];
            } else $reason = 'не известна';

            if(isset($result['applicationId'])) {
                $order->application_id = $result['applicationId'];
                $order->save();

                $status = Helper::check($order);
                $counter = 0;

                if(!isset($status)) {
                    do {
                        $counter++;
                        sleep(5);
                        $status = Helper::check($order);
                        if($status !== null || $counter == 5 ) break;

                    }
                    while($status === null || $counter <= 5);
                }
               return redirect()->route('statistic.mfo', [$status])->with('flash_message_success', 'Заявка отправлена, статус - '. $status);

            } else return back()->with('flash_message_error', 'Данные не отправились! Причина - '. $reason);


        }



    }

    public function downloadSpecification(MfoOrder $order) {
        $items = json_decode($order['items'], true);
        $products =[];
        foreach ($items as $item) {
            $products[]=$item['name'];
        }
        $product =implode(" ", $products);
        isset($order['initial_fee'])? $initialFee=$order['initial_fee']: $initialFee=0;

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

    public function checkStatus(MfoOrder $order) {

        $status = Helper::check($order);


            if(isset($status)) {
                return back()->with('flash_message_success', $status);
            } else return back()->with('flash_message_error', 'Статус не определен');
    }
    public function moreData(MfoOrder $order) {

        $user = User::findOrFail(Auth::id());
        $additional = AdditionalOrderData::where('order_id', $order->id)->first();

        return view('order.moreDataMfo', compact('order', 'user', 'additional'));
    }

    public function sendMoreData(Request $request) {

        $applicationId = $request->input('application_id');
        $post = [
            'applicationId'=> $applicationId,
        ];
//dump($request);
        $passportPhoto1 = $request->file('passport_photo1');

        $passportPhoto2 = $request->file('passport_photo2');
        $filesFront = $request->file('files_photo_front');
        $filesBack = $request->file('files_photo_back');
        $photoWithPassport = $request->file('photo_with_passport');
        $employmentType = $request->input('employment_type');
        $employer = $request->input('employer');
        $position = $request->input('position');
        $additionalPhone = $request->input('additional_phone');
        $employmentPeriod = $request->input('employment_period');
        if(isset($passportPhoto1) && isset($passportPhoto2)) {
            $post['passportPhoto']['page1']['extension'] = $passportPhoto1->getClientOriginalExtension();
            $post['passportPhoto']['page1']['encodeString'] =base64_encode(base64_encode(file_get_contents($passportPhoto1->path())));
            $post['passportPhoto']['page2']['extension'] = $passportPhoto2->getClientOriginalExtension();
            $post['passportPhoto']['page2']['encodeString'] = base64_encode(base64_encode(file_get_contents($passportPhoto2->path())));
        }
        if(isset($filesFront) && isset($filesBack)) {
            $post['additionalFiles']['photoFront']['extension'] = $filesFront->getClientOriginalExtension();
            $post['additionalFiles']['photoFront']['encodeString'] = base64_encode(base64_encode(file_get_contents($filesFront->path())));
            $post['additionalFiles']['photoBack']['extension'] = $filesBack->getClientOriginalExtension();
            $post['additionalFiles']['photoBack']['encodeString'] = base64_encode(base64_encode(file_get_contents($filesBack->path())));
        }
        if(isset($employmentType)) {
            $post['employment']['employment_type'] = $employmentType;
            $post['employment']['employer'] = $employer;
            $post['employment']['employment_period'] = $employmentPeriod;
            $post['employment']['position'] = $position;
        }
        if(isset($photoWithPassport)) {
            $post['photoWithPassport']['extension'] = $photoWithPassport->getClientOriginalExtension();
            $post['photoWithPassport']['encodeString'] = base64_encode(base64_encode(file_get_contents($photoWithPassport->path())));
        }
        if(isset($additionalPhone)) {
            $phone = new ReplacePhone();
            $additionalPhone = $phone->replace($additionalPhone);
            $post['additionalPhoneNumber'] = $additionalPhone;
        }
//dd(json_encode($post, JSON_UNESCAPED_UNICODE));
//dump($post);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/enrichment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($post, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Token '.env('MFO_TOKEN'),
                'Accept-Charset: utf-8'
            ),
        ));

        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);


//        $response =  Http::withHeaders([
//            'Authorization'=> 'Token '.env('MFO_TOKEN'),
//            'Content-Type'=> 'application/x-www-form-urlencoded'
//        ])->post('https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/enrichment', json_encode($post, JSON_UNESCAPED_UNICODE));

//        $result = json_decode($response, true);

        if($httpcode==200) {
            return redirect()->route('statistic.mfo')->with('flash_message_success', 'Данные отправились');
        } else  return back()->with('flash_message_error', 'Данные не отправились');


    }

    public function signOrder(MfoOrder $order) {

        $post = ['applicationId'=>$order->application_id];


        $response =  Http::withHeaders([
            'Authorization'=> 'Token '.env('MFO_TOKEN'),
            'Content-Type'=> 'application/x-www-form-urlencoded'
        ])->post('https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/signCode/send', $post);
        $result = json_decode($response, true);
        if($response->status() ==200) {
            $response = 'Запрос на подписание отправлен';
        } else $response = 'Запрос на подписание не удалось отправить';

        return view('order.signMfo', compact('order','response'));
    }

    public function signMfoSend(Request $request) {
        $code = $request->input('sms_code');
        $applicationId = $request->input('application_id');

        $post = ['applicationId'=>$applicationId, "signCode"=>(int)$code];



        $response =  Http::withHeaders([
            'Authorization'=> 'Token '.env('MFO_TOKEN'),
            'Content-Type'=> 'application/x-www-form-urlencoded'
        ])->post('https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/sign', $post);
        $result = json_decode($response, true);
        if($response->status() ==200) {
            return redirect()->route('statistic.mfo')->with('flash_message_success', 'Данные отправились');
        } else {
            return back()->with('flash_message_error', 'Данные не отправились');
        }
    }
}
