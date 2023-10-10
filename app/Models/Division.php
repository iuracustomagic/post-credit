<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions';
    protected $guarded = false;


    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    const TYPE_POS = 1;
    const TYPE_INTERNET = 2;

    static function getStatus() {
        return [
            self::STATUS_ACTIVE => 'Активна',
            self::STATUS_NOT_ACTIVE => 'Не активна',
        ];
    }

    public function getStatusTitleAttribute() {
        return self::getStatus()[$this->status];
    }

    static function getType() {
        return [
            self::TYPE_POS => 'РОС - магазин',
            self::TYPE_INTERNET => 'Интернет магазин',
        ];
    }

    public function getTypeTitleAttribute() {
        return self::getType()[$this->type];
    }
    public function getPlanValueAttribute() {
        $plan = Rate::where('id', $this->plan_id)->first();
        if($plan) return $plan['value'];
        else return '';

    }
}
