<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = false;
//    protected $fillable = [
//        'name',
//        'email',
//        'password',
//    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    const STATUS_ACTIVE = 1;
    const STATUS_NOT_ACTIVE = 0;

    static function getStatus() {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_NOT_ACTIVE => 'Отключен',
        ];
    }

    public function getStatusTitleAttribute() {
        return self::getStatus()[$this->status];
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }
    public function manager(){
        return $this->hasOne('App\Models\User', 'id', 'manager_id');
    }

    public function getCreditBonusAttribute()
    {
        $request= request();
        $companies = Company::where('created_by', $this->id)->get();
        $managerBonus = ManagerBonusSetting::where('manager_id', $this->id)->first();
        $credit1 = Rate::where('id',1)->first();
        $credit2 = Rate::where('id',2)->first();
        $credit3 = Rate::where('id',3)->first();
        $credit4 = Rate::where('id',4)->first();
        if(isset($managerBonus)) {
            $reward1 = $managerBonus['credit_1'];
            $reward2 = $managerBonus['credit_2'];
            $reward3 = $managerBonus['credit_3'];
            $reward4 = $managerBonus['credit_4'];
        } else {

            $reward1 = $credit1['reward'];
            $reward2 = $credit2['reward'];
            $reward3 = $credit3['reward'];
            $reward4 = $credit4['reward'];
        }

        $value1 = 0;
        $percent1 = 0;
        $value2 = 0;
        $percent2 = 0;
        $value3 = 0;
        $percent3 = 0;
        $value4 = 0;
        $percent4 = 0;

        foreach ($companies as $company) {
            $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit1['value'])->get();
        foreach ($orders as $order) {
            $value1 += $order['sum_credit'];
        }
           $percent1 = $value1 * $reward1/100;

        }
        foreach ($companies as $company) {
            $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit2['value'])->get();
            foreach ($orders as $order) {
                $value2 += $order['sum_credit'];
            }
            $percent2 = $value2 * $reward2/100;
        }

        foreach ($companies as $company) {
            $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit3['value'])->get();
            foreach ($orders as $order) {
                $value3 += $order['sum_credit'];
            }
            $percent3 = $value3 * $reward3/100;
        }
        foreach ($companies as $company) {
            $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit4['value'])->get();
            foreach ($orders as $order) {
                $value4 += $order['sum_credit'];
            }
            $percent4 = $value4 * $reward4/100;
        }

        return $percent1 + $percent2 + $percent3 + $percent4;

    }
    public function getSmsBonusAttribute()
    {
        $request= request();
        $companies = Company::where('created_by', $this->id)->get();
        $managerBonus = ManagerBonusSetting::where('manager_id', $this->id)->first();
        if(isset($managerBonus)) {
            $smsReward = $managerBonus['sms']/100;
        } else {
            $sms = Rate::where('type', 'sms')->first();
            $smsReward =$sms['reward']/100;
        }


        $smsBonus =0;
        foreach ($companies as $company) {
            $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->where('company_id', $company->id)->where('status', 'signed')->get();
            foreach ($orders as $order) {
                $smsBonus +=  $order['price_sms']-(120*$order['term_credit']);
            }

        }
        return $smsBonus*$smsReward;
    }

    public function getRefBonusAttribute()
    {
        $request= request();
        $refUsers = User::where('manager_id', $this->id)->get();
        $ref = Rate::where('type', 'referral')->first();
        $refReward=$ref['reward'];
        $managerBonus = ManagerBonusSetting::where('manager_id', $this->id)->first();
        $refValue = 0;
foreach ($refUsers as $refUser) {
    $companies = Company::where('created_by', $refUser->id)->get();
    $credit1 = Rate::where('id',1)->first();
    $credit2 = Rate::where('id',2)->first();
    $credit3 = Rate::where('id',3)->first();
    $credit4 = Rate::where('id',4)->first();

    if(isset($managerBonus)) {
        $reward1 = $managerBonus['credit_1'];
        $reward2 = $managerBonus['credit_2'];
        $reward3 = $managerBonus['credit_3'];
        $reward4 = $managerBonus['credit_4'];
        $refReward=  $managerBonus['referral'];
    } else {

        $reward1 = $credit1['reward'];
        $reward2 = $credit2['reward'];
        $reward3 = $credit3['reward'];
        $reward4 = $credit4['reward'];

    }

    $value1 = 0;
    $percent1 = 0;
    $value2 = 0;
    $percent2 = 0;
    $value3 = 0;
    $percent3 = 0;
    $value4 = 0;
    $percent4 = 0;

    foreach ($companies as $company) {
        $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
        })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit1['value'])->get();
        foreach ($orders as $order) {
            $value1 += $order['sum_credit'];
        }
        $percent1 = $value1 * $reward1/100;

    }
    foreach ($companies as $company) {
        $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
        })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit2['value'])->get();
        foreach ($orders as $order) {
            $value2 += $order['sum_credit'];
        }
        $percent2 = $value2 * $reward2/100;
    }

    foreach ($companies as $company) {
        $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
        })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit3['value'])->get();
        foreach ($orders as $order) {
            $value3 += $order['sum_credit'];
        }
        $percent3 = $value3 * $reward3/100;
    }
    foreach ($companies as $company) {
        $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
        })->where('company_id', $company->id)->where('status', 'signed')->where('rate', $credit4['value'])->get();
        foreach ($orders as $order) {
            $value4 += $order['sum_credit'];
        }
        $percent4 = $value4 * $reward4/100;
    }
    $refValue+= $percent1 + $percent2 + $percent3 + $percent4;
}
        return ($refValue * $refReward) /100;
    }
    public function getCreditChooseAttribute() {
        $request= request();
        $divisions = Division::where('created_by', $this->id)->where('find_credit', 'on')->get();
        $value=0;
        if($divisions) {
            foreach ($divisions as $division) {
                $orders = Order::when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                    return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
                })->where('division_id', $division->id)->where('status', 'signed')->get();
                if($orders) {
                    $value+=$division['find_credit_value']*count($orders);
                }


            }
            if($value>=1599 && $value<=2599) {
                return $value - 1599;
            }else if($value>2599){
                $halfValue= ($value-2599)/2;
                return 1000+$halfValue;
            } else return 0;
        }
        return $value;
    }
    public function getLeaderManagerAttribute() {
        $company= Company::where('leader_id', $this->id)->first();
        $manager = User::where('id',$company->created_by )->first();
        return $manager['first_name'].' '. $manager['last_name'].' '. $manager['surname'].', тел:   '. $manager['phone'];
    }
    public function getSalesmanManagerAttribute() {
        $userCompany = UserCompany::where('user_id', $this->id)->first();
        $company= Company::where('id', $userCompany->company_id)->first();
        if(isset($company->created_by)) {
            $manager = User::where('id',$company->created_by )->first();
            return $manager['first_name'].' '. $manager['last_name'].' '. $manager['surname'].', тел:   '. $manager['phone'];
        } else return '-';
//        $manager = User::where('id',$company->created_by )->first();

    }
    }
