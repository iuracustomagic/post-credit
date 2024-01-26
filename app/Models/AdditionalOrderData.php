<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalOrderData extends Model
{
    protected $table = 'additional_order_data';
    protected $guarded = false;

    const CAUSE_1 = 1;
    const CAUSE_2 = 2;
    const CAUSE_3 = 3;
    const CAUSE_4 = 4;
    const CAUSE_5 = 5;

    static function getCause() {
        return [
            self::CAUSE_1 => 'Невозможность прочесть информацию в документе',
            self::CAUSE_2 => 'Края документа обрезаны',
            self::CAUSE_3 => 'Нечеткая фотография',
            self::CAUSE_4 => 'Прописка сфотографирована не в развороте',
            self::CAUSE_5 => 'Невозможно распознать фото на документе',


        ];
    }
    public function getCauseTitleAttribute() {
        return self::getCause()[$this->repeat_cause];
    }
}
