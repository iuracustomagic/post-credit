<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Company extends Model
{
//    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'companies';
    protected $guarded = false;


    const STATUS_SEND = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_NOT_ACTIVE = 3;

    static function getStatus() {
        return [
            self::STATUS_SEND => 'Новая',
            self::STATUS_ACTIVE => 'Активная',
            self::STATUS_NOT_ACTIVE => 'Отключена',
        ];
    }

    public function getStatusTitleAttribute() {
        return self::getStatus()[$this->status];
    }

    public function divisions()
    {
        return $this->hasMany('App\Models\Division', 'company_id','id');
    }
    public function salesmen()
    {
        return $this->hasMany('App\Models\UserCompany', 'company_id','id');
    }
}
