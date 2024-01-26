<?php


namespace App\Classes;


use App\Models\AdditionalOrderData;
use App\Models\MfoOrder;
use Illuminate\Database\Eloquent\Model;

class Helper
{
    public static function check(MfoOrder $order)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-ext.paylate.ru/paylateservice/api/posCredit/v3/status?request={"applicationId":"' . $order['application_id'] . '"}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Token ' . env('MFO_TOKEN')
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
//        dd($response);
        $result = json_decode($response, true);
//        dd($result);
        if (isset($result['applicationStatus'])) {
            MfoOrder::where('id', $order['id'])->update(['status' => $result['applicationStatus']]);
            switch ($result['applicationStatus']) {
                case 'processing':
                    $status = 'В процессе';
                    break;
                case 'needMoreData':
                    $status = 'Нужны еще данные';
                    break;
                case 'approved':
                    $status = 'Одобрена';
                    break;
                case 'merged':
                    $status = 'Одобрена но есть заем';
                    break;
                case 'signed':
                    $status = 'Подписана';
                    break;
                case 'closed':
                    $status = 'Анулирование';
                    break;
                case 'declined':
                    $status = 'Отказ';
                    break;
                case 'repaid':
                    $status = 'Погашена';
                    break;
                case 'isOverdue':
                    $status = 'В просрочке';
                    break;
                default:
                    $status = 'Не известен';

            }
            if (isset($result['requestedPersonalData'])) {
//                dd($result['requestedPersonalData']);
                $existingAdditionalData = AdditionalOrderData::where('order_id', $order['id'])->first();
                if (isset($existingAdditionalData)) {
                    $additionalData = $existingAdditionalData;
                } else {
                    $additionalData = new AdditionalOrderData();
                    $additionalData['order_id'] = $order['id'];
                }


                if ($result['requestedPersonalData']['passportRequired']) {
                    $additionalData['passport'] = 1;
                } else $additionalData['passport'] = false;
                if ($result['requestedPersonalData']['employmentRequired']) {
                    $additionalData['employment'] = 1;
                } else $additionalData['employment'] = false;
                if ($result['requestedPersonalData']['filesRequired']) {
                    $additionalData['files'] = 1;
                } else $additionalData['files'] = false;
                if ($result['requestedPersonalData']['photoWithPassportRequired']) {
                    $additionalData['photo_with_passport'] = 1;
                } else $additionalData['photo_with_passport'] = false;
                if ($result['requestedPersonalData']['registrationAddressRequired']) {
                    $additionalData['registration_address'] = 1;
                } else $additionalData['registration_address'] = false;
                if ($result['requestedPersonalData']['residenceAddressRequired']) {
                    $additionalData['residence_address'] = 1;
                } else $additionalData['residence_address'] = false;
                if ($result['requestedPersonalData']['incomeAmountRequired']) {
                    $additionalData['income_amount'] = 1;
                } else $additionalData['income_amount'] = false;
                if ($result['requestedPersonalData']['additionalPhoneNumberRequired']) {
                    $additionalData['additional_phone'] = 1;
                } else $additionalData['additional_phone'] = false;
                if ($result['requestedPersonalData']['foreignPassportRequired']) {
                    $additionalData['foreign_passport'] = 1;
                } else $additionalData['foreign_passport'] = false;
                if ($result['requestedPersonalData']['permanentResidencyRequired']) {
                    $additionalData['permanent_residency'] = 1;
                } else $additionalData['permanent_residency'] = false;
                if ($result['requestedPersonalData']['residencePermitRequired']) {
                    $additionalData['residence_permit'] = 1;
                } else $additionalData['residence_permit'] = false;
                if ($result['requestedPersonalData']['permanentRegistrationRequired']) {
                    $additionalData['permanent_registration'] = 1;
                } else $additionalData['permanent_registration'] = false;
                if ($result['requestedPersonalData']['temporaryRegistrationRequired']) {
                    $additionalData['temporary_registration'] = 1;
                } else $additionalData['temporary_registration'] = false;
                if ($result['requestedPersonalData']['patentRequired']) {
                    $additionalData['patent'] = 1;
                } else $additionalData['patent'] = false;
                if ($result['requestedPersonalData']['patentPaymentReceiptRequired']) {
                    $additionalData['patent_payment'] = 1;
                } else $additionalData['patent_payment'] = false;
                if ($result['requestedPersonalData']['snilsRequired']) {
                    $additionalData['snils'] = 1;
                } else $additionalData['snils'] = false;
                if ($result['requestedPersonalData']['repeatCause']) {
                    $additionalData['repeat_cause'] = $result['requestedPersonalData']['repeatCause'];
                }
                $additionalData->save();
            }

        } else $status =null;
        return $status;
    }
}
