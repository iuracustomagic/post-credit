<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionInstallment extends Model
{
    protected $table = 'division_installments';
    protected $guarded = false;

    public function division(){
        return $this->hasOne('App\Models\Division', 'id', 'division_id');
    }
    public function installment(){
        return $this->hasOne('App\Models\Rate', 'id', 'installment_id');
    }
}
