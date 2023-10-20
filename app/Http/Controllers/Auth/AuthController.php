<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\Company;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(){
        return view('layouts.login');
    }
    public function postLogin(Request $request){
        $request->validate([
            'login'=> 'required|string',
            'password'=>'required'
        ]);
        $data = $request->input();
//        dd($data);

        if (Auth::attempt(['login' => $data['login'], 'password' => $data['password']])) {
            Session::put('adminSession',$data['login']);
            if(Auth::user()->role_id == 4) {
                $userCompany = UserCompany::where('user_id', Auth::id())->first();
                $company = Company::findOrFail($userCompany->company_id);
                if($company->status == 2) {
                    return redirect()->route('statistic');
                } else return back()->with('message_error','Вы не можете пока войти, Организация не одобрена');

            } else if(Auth::user()->role_id == 3) {
                $company = Company::where('leader_id', Auth::id())->first();

                if($company->status == 2) {
                    return redirect()->route('statistic');
                } else return back()->with('message_error','Вы не можете пока войти, Организация не одобрена');
            } else return redirect()->route('statistic');
        } else {
            return redirect()->back()->with('message_error','Invalid Login or Password');
        }
    }
    public function register(){

        return view('register.manager_register');
    }
    public function managerSave(StoreRequest $request){

        $data = $request->validated();
        $manager = User::where('ref_number', $data['ref_number'])->first();
        $data['ref_number'] = $data['manager_id'];
        $data['manager_id']=$manager->id;
        $data['ref_link']=1;

//        dd($data);
        User::firstOrCreate(['email' => $data['email']], $data);
        return redirect()->route('login');
    }

    public function logout(){
        auth()->logout();
        return redirect()->route('login');
    }
}
