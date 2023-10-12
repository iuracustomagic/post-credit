<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\Company;
use App\Models\ManagerBonusSetting;
use App\Models\Rate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $users = User::where('role_id', 2)->get();
        $title = 'Менеджеры';
        return view('user.manager.index', compact('users', 'title'));
    }
    public function create()
    {
        $roles = Role::all();
        $managers = User::where('role_id', 2)->get();
        $creditRates = Rate::where('type', 'credit')->get();
        $smsRate = Rate::where('type', 'sms')->first();
        $referralRate = Rate::where('type', 'referral')->first();
        return view('user.manager.create', compact('roles', 'managers', 'creditRates', 'smsRate', 'referralRate'));
    }
    public function show(User $user)
    {
        $manager = User::where('id', $user->manager_id)->first();
        return view('user.manager.show', compact('user', 'manager'));
    }
    public function store(StoreRequest $request)
    {
        $data = $request->validated();

//        dd($data);

        $credit1 = $data['credit_1'];
        $credit2 = $data['credit_2'];
        $credit3 = $data['credit_3'];
        $credit4 = $data['credit_4'];
        $credit5 = $data['credit_15'];
        $sms = $data['sms'];
        $referral = $data['referral'];
        unset($data['credit_1'], $data['credit_2'], $data['credit_3'], $data['credit_4'], $data['credit_15'], $data['sms'],$data['referral']);
       $user = User::firstOrCreate(['email' => $data['email']], $data);
       $managerBonus = [
           'manager_id'=>$user->id,
           'credit_1'=>$credit1,
           'credit_2'=>$credit2,
           'credit_3'=>$credit3,
           'credit_4'=>$credit4,
           'credit_5'=>$credit5,
           'sms'=>$sms,
           'referral'=>$referral,
       ];

       ManagerBonusSetting::firstOrCreate($managerBonus);
        return redirect()->route('manager.index');
    }

    public function edit(User $user)
    {
        $managers = User::where('role_id', 2)->where('id', '!=', $user->id)->get();
        $myManagers = User::where('manager_id', $user->id)->get();
        $manager = User::where('id', $user->manager_id)->first();
        $managerBonus = ManagerBonusSetting::where('manager_id', $user->id)->first();
        $creditRates = Rate::where('type', 'credit')->get();
        $companies=Company::where('created_by', $user->id )->get();

        if(isset($managerBonus)) {
            foreach ($creditRates as $key=> $creditRate) {
                if($creditRate['id'] ==1){
                    $creditRate['reward']=$managerBonus->credit_1;
                }
                if($creditRate['id'] ==2){
                    $creditRate['reward']=$managerBonus->credit_2;
                }
                if($creditRate['id'] ==3){
                    $creditRate['reward']=$managerBonus->credit_3;
                }
                if($creditRate['id'] ==4){
                    $creditRate['reward']=$managerBonus->credit_4;
                }   if($creditRate['id'] ==15){
                    $creditRate['reward']=$managerBonus->credit_5;
                }
            }

            $smsRate = $managerBonus['sms'];
            $referralRate = $managerBonus['referral'];
        } else {
            $smsRateArr = Rate::where('type', 'sms')->first();
            $referralRateArr = Rate::where('type', 'referral')->first();
            $smsRate = $smsRateArr->reward;
            $referralRate = $referralRateArr->reward;
        }

        if($manager) {
            $manager_id = $manager->id;
        } else  $manager_id = 0;
//     dd($manager);
        return view('user.manager.edit', compact('user', 'managers','myManagers', 'companies','manager_id', 'creditRates', 'smsRate', 'referralRate'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        if (isset($_POST['save'])) {
            $data = $request->validated();
            $managerBonus = [
                'credit_1'=>$data['credit_1'],
                'credit_2'=>$data['credit_2'],
                'credit_3'=>$data['credit_3'],
                'credit_4'=>$data['credit_4'],
                'credit_5'=>$data['credit_15'],
                'sms'=>$data['sms'],
                'referral'=>$data['referral'],
            ];

            unset($data['credit_1'], $data['credit_2'], $data['credit_3'], $data['credit_4'], $data['credit_15'],$data['sms'],$data['referral']);
            if(!isset($data['password'])) {
                unset($data['password']);
            }
            $user->update($data);
//            ManagerBonusSetting::where('manager_id', $user->id)->update($managerBonus);
            ManagerBonusSetting::updateOrCreate([ 'manager_id'=>$user->id,],$managerBonus);
            return back()->with('flash_message_success', 'Данные обновились');
        } else if (isset($_POST['search'])) {
            $previousUrl = app('url')->previous();
            return redirect()->to($previousUrl.'?'.http_build_query(['date_from'=>$request->date_from, 'date_to'=>$request->date_to]));
//            return back()->with('date_from', $user->id);
        }

    }

    public function delete(User $user)
    {
        $managerBonus=ManagerBonusSetting::where('manager_id', $user->id)->first();
        if($managerBonus){
            $managerBonus->delete();
        }
        $user->delete();
        return redirect()->route('manager.index');
    }

    public function bonusSetting()
    {
//        dd('here');
        $credit1 = Rate::where('id', 1)->first();
        $credit2 = Rate::where('id', 2)->first();
        $credit3 = Rate::where('id', 3)->first();
        $credit4 = Rate::where('id', 4)->first();
        $sms = Rate::where('type', 'sms')->first();
        $referral = Rate::where('type', 'referral')->first();

        return view('user.manager.bonus_setting', compact('credit1', 'credit2', 'credit3', 'credit4', 'sms', 'referral'));
    }
    public function bonusSave(Request $request)
    {

        $credit1 = Rate::where('id', 1)->update(['reward'=>$request->credit_1]);
        $credit2 = Rate::where('id', 2)->update(['reward'=>$request->credit_2]);
        $credit3 = Rate::where('id', 3)->update(['reward'=>$request->credit_3]);
        $credit4 = Rate::where('id', 4)->update(['reward'=>$request->credit_4]);
        $sms = Rate::where('type', 'sms')->update(['reward'=>$request->sms]);
        $referral = Rate::where('type', 'referral')->update(['reward'=>$request->referral]);

        return back()->with('flash_message_success', 'Данные обновились');
    }
}
