<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $guarded = false;

    const STATUS_NEW = 'new';
    const STATUS_INPROGRESS = 'inprogress';
    const STATUS_APPROVED = 'approved';
    const STATUS_SIGNED = 'signed';
    const STATUS_CANCELED = 'canceled';
    const STATUS_REJECTED = 'rejected';

    static function getStatus() {
        return [
            self::STATUS_NEW => 'Новая',
            self::STATUS_INPROGRESS => 'В процессе',
            self::STATUS_APPROVED => 'Одобрена',
            self::STATUS_SIGNED => 'Подписана',
            self::STATUS_CANCELED => 'Отменена',
            self::STATUS_REJECTED => 'Отказ',
        ];
    }

    public function getStatusTitleAttribute() {
        return self::getStatus()[$this->status];
    }
    public function getCompanyNameAttribute()
    {

        $company = Company::where('id', $this->company_id )->first();
        if($company) {
            return $company['name'];
        } else return '-';
    }
    public function getDivisionAddressAttribute()
    {

        $division = Division::where('id', $this->division_id )->first();
        if($division) {
                return $division['address'];
            } else return '-';
//        $userDivision = UserDivision::where('user_id', $this->salesman_id)->first();
//        if($userDivision) {
//
//            $division = Division::where('id', $userDivision->division_id )->first();
//            if($division) {
//                return $division['address'];
//            } else return '-';
//
//        } else return '-';


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
}
