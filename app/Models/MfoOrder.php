<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MfoOrder extends Model
{
    protected $table = 'mfo_orders';
    protected $guarded = false;


    const STATUS_NEW = 'new';
    const STATUS_INPROGRESS = 'processing';
    const STATUS_APPROVED = 'approved';
    const STATUS_MORE_DATA = 'needMoreData';
    const STATUS_MERGED = 'merged';
    const STATUS_SIGNED = 'signed';
    const STATUS_CLOSED = 'closed';
    const STATUS_REJECTED = 'declined';
    const STATUS_REPAID = 'repaid';
    const STATUS_OVERDUE = 'isOverdue';
    const STATUS_FAILED = 'failed';

    const TYPE_CREDIT = 1;
    const TYPE_INSTALLMENT = 2;
    const TYPE_MFO = 3;
    const TYPE_NULL = null;


    static function getStatus() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_INPROGRESS => 'В процессе',
            self::STATUS_APPROVED => 'Одобрена',
            self::STATUS_MORE_DATA => 'Нужны еще данные',
            self::STATUS_MERGED => 'Одобрена но есть заем',
            self::STATUS_SIGNED => 'Подписана',
            self::STATUS_CLOSED => 'Анулирована',
            self::STATUS_REJECTED => 'Отказ',
            self::STATUS_REPAID => 'Погашена',
            self::STATUS_OVERDUE => 'В просрочке',
            self::STATUS_FAILED => 'Не отправлено',
        ];
    }

    static function getType() {
        return [
            self::TYPE_CREDIT => 'Кредит',
            self::TYPE_INSTALLMENT => 'Рассрочка',
            self::TYPE_MFO => 'МФО',
            self::TYPE_NULL => '',


        ];
    }
    public function getStatusTitleAttribute() {
        return self::getStatus()[$this->status];
    }
    public function getTypeTitleAttribute() {
        return self::getType()[$this->credit_type];
    }
    public function getCompanyNameAttribute()
    {

        $company = Company::where('id', $this->company_id )->first();
        if($company) {
            return $company['name'];
        } else return '-';
    }

    public function getDivisionNameAttribute()
    {

        $division = Division::where('id', $this->division_id )->first();
        if($division) {
            return $division['name'];
        } else return '-';



    }
    public function getDivisionAddressAttribute()
    {

        $division = Division::where('id', $this->division_id )->first();
        if($division) {
            return $division['address'];
        } else return '-';


    }
    public function getSmsValueAttribute()
    {

        $division = Division::where('id', $this->division_id )->first();
        if($division) {
            return $division['price_sms'] * $this->term_credit;
        } else return '-';

    }

    public function getProductNameAttribute()
    {

        $items = json_decode($this->items);
        $nameArr = [];
        foreach ((array)$items as $item) {
            $nameArr[]=$item->name;
        }
        $nameStr = implode(',', $nameArr);

        return $nameStr;
    }
    public function getManagerNameAttribute()
    {
        $company=Company::where('id', $this->company_id)->first();
        if($company) {
            $manager = User::where('id', $company->created_by )->first();
            if($manager) {
                return $manager['first_name'].' '.$manager['last_name'].' '.$manager['surname'];
            } else return '-';
        }else return '-';


    }
    public function getManagerPhoneAttribute()
    {
        $company=Company::where('id', $this->company_id)->first();
        if($company) {
            $manager = User::where('id', $company->created_by )->first();
            if($manager) {
                return $manager['phone'];
            } else return '-';
        }else return '-';


    }
    public function getFindCreditValueAttribute()
    {
        if($this->find_credit =='on') {
            $division=Division::where('id', $this->division_id)->first();
            if($division) {
                return $division->find_credit_value;
            } else return '-';
        } else return '-';

    }
    public function getClientNameAttribute()
    {
        $client=MfoClient::where('id', $this->customer_id)->first();
        if($client) {
                return $client['first_name'].' '.$client['last_name'].' '.$client['surname'];
            } else return '-';

    }
    public function getClientPhoneAttribute()
    {
        $client=MfoClient::where('id', $this->customer_id)->first();
        if($client) {
            return $client['phone'];
        } else return '-';
    }

    public function getClientBirthdayAttribute()
    {
        $client=MfoClient::where('id', $this->customer_id)->first();
        if($client) {
            return $client['birthday'];
        } else return '-';
    }
}
