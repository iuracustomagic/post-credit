<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionImage extends Model
{
    protected $table = 'division_images';
    protected $guarded = false;


    public function division()
    {
        return $this->belongsTo('App\Division', 'division_id');
    }
}
