<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsNotification extends Model
{
    protected $table = 'sms_notifications';
    protected $guarded = false;

    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    static function getStatus() {
        return [
            self::STATUS_PENDING => 'Отправляется',
            self::STATUS_SENT => 'Отправлено',
            self::STATUS_FAILED => 'Не отправлено',
        ];
    }
    public function getStatusTitleAttribute() {
        return self::getStatus()[$this->status];
    }

    public function getUserNameAttribute()
    {
        $user = User::where('id', $this->user_id )->first();
        if($user) {
            return $user['first_name'].' '.$user['last_name'].' '.$user['surname'];
        } else return '-';

    }
    public function getUserPhoneAttribute()
    {
        $user = User::where('id', $this->user_id )->first();
        if($user) {
            return $user['phone'];
        } else return '-';

    }
}
