<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salesman\StoreRequest;
use App\Http\Requests\Salesman\UpdateRequest;
use App\Models\Company;
use App\Models\Division;
use App\Models\LeaderPassword;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesmanController extends Controller
{
    public function index()
    {
        if(Auth::user()->role_id == 2) {
//            $users= User::where('manager_id', Auth::id())->where('role_id', '4')->get();
            $companies = Company::where('created_by',Auth::id() )->get();
            if(isset($companies)) {
                $userCompanies=[];
                foreach($companies as $company) {
                    $userCompany = UserCompany::where('company_id',$company->id)->get();
                    foreach ($userCompany as $item) {
                        $userCompanies[] =$item;
                    }

                }

            } else $userCompanies =null;

//dd($userCompanies);
            $users = [];
            if($userCompanies) {
                foreach($userCompanies as $item) {
                    $user = User::where('id', $item->user_id)->first();
                    $users[] =$user;
                }

            }
        } else $users=  User::where('role_id', '4')->get();
        $title = 'Продавцы';

        return view('user.salesman.index', compact('users', 'title'));
    }
    public function create()
    {
        if(Auth::user()->role_id == 2) {
            $companies= Company::where('created_by', Auth::id())->get();
            $divisions= Division::where('created_by', Auth::id())->get();
            foreach ($companies as $company){
                $divisionList = Division::where('company_id', $company->id)->get();
                $company['divisions'] = $divisionList;
            }

        } else {
            $companies = Company::all();
            $divisions = Division::all();
            foreach ($companies as $company){
                $divisionList = Division::where('company_id', $company->id)->get();
                $company['divisions'] = $divisionList;
            }
        }

//dd($companies);
        return view('user.salesman.create', compact('companies', 'divisions'));
    }
    public function show(User $user)
    {
        $userCompany = UserCompany::where('user_id', $user->id)->first();
        if ($userCompany) {
            $company = Company::where('id', $userCompany->company_id)->first();
            $company_name = $company->name;
        } else $company_name ='';

        $userDivision = UserDivision::where('user_id', $user->id)->first();
        if ($userDivision) {
            $division = Division::where('id',$userDivision->division_id )->first();
            $division_name = $division->name;
        } else $division_name ='';


        return view('user.salesman.show', compact('user','company_name', 'division_name'));
    }
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $companyId = $data['company_id'];
        $division_id = $data['division_id'];
        unset($data['company_id'],  $data['division_id']);

      $user = User::firstOrCreate( $data);
     $userCompany = UserCompany::firstOrCreate([
          'user_id'=>$user->id,
          'company_id'=>$companyId
      ]);
     $userDivision = UserDivision::firstOrCreate([
          'user_id'=>$user->id,
          'division_id'=>$division_id
      ]);
        if($user && $userCompany && $userDivision) {
            return redirect()->route('salesman.index')->with('flash_message_success', 'Данные сохранились!');
        } else return back()->with('flash_message_error', 'Данные не сохранились!');

    }

    public function edit(User $user)
    {
        if(Auth::user()->role_id == 2) {
            $companies= Company::where('created_by', Auth::id())->get();
            foreach ($companies as $company){
                $divisionList = Division::where('company_id', $company->id)->get();
                $company['divisions'] = $divisionList;
            }

        } else {
            $companies = Company::all();
            foreach ($companies as $company){
                $divisionList = Division::where('company_id', $company->id)->get();
                $company['divisions'] = $divisionList;
            }
        }

        if(Auth::user()->role_id == 2) {
            $divisions= Division::where('created_by', Auth::id())->get();

        } else $divisions = Division::all();

        $userCompany = UserCompany::where('user_id', $user->id)->first();
        if ($userCompany) {
            $company_id = $userCompany->company_id;
        } else $company_id =0;
        $userDivision = UserDivision::where('user_id', $user->id)->first();
        if ($userDivision) {
            $division_id = $userDivision->division_id;
            $divisionOld = Division::where('company_id', $company_id)->get();
        } else {
            $divisionOld = null;
            $division_id =0;
        }

        return view('user.salesman.edit', compact('user', 'companies', 'divisions', 'company_id', 'divisionOld', 'division_id'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        $data = $request->validated();
//        dd($data);
        $companyId = $data['company_id'];
        $division_id = $data['division_id'];
        unset($data['company_id'],  $data['division_id']);
        if(!isset($data['password'])) {
            unset($data['password']);
        }

        if($companyId) {
//            UserCompany::firstOrCreate([
//                'user_id'=>$user->id,
//                'company_id'=>$companyId
//            ]);
            UserCompany::where('user_id', $user->id)->update(['company_id'=> $companyId]);
        }
        if($division_id) {
//            UserDivision::firstOrCreate([
//                'user_id'=>$user->id,
//                'division_id'=>$division_id
//            ]);
            UserDivision::where('user_id', $user->id)->update(['division_id'=> $division_id]);
        }

        $user->update($data);
       return redirect()->route('salesman.index');
    }

    public function delete(User $user)
    {
        $userCompany = UserCompany::where('user_id', $user->id)->first();
        $userDivision = UserDivision::where('user_id', $user->id)->first();
        $userDivision->delete();
        $userCompany->delete();
        $user->delete();
        return redirect()->route('salesman.index');
    }
}
