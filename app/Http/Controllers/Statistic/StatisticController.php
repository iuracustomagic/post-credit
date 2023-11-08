<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Division;
use App\Models\Order;
use App\Models\UserCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class StatisticController extends Controller
{
    public function main(Request $request)
    {

        if(Auth::user()->role_id == 1) {
            $companies = Company::all();
            $all = Order::all();
            $approved = Order::where('status', 'approved')->get();
            $rejected= Order::where('status', 'rejected')->get();
            $signed= Order::where('status', 'signed')->get();

            $orders = Order::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->when($request->status == null && $request->company == null && $request->date_from == null && $request->date_to == null, function ($q) use ($request) {
                return $q->take(100);
            })->orderBy('created_at', 'DESC')->get();
//dd($orders);


            return view('main.admin', compact('orders', 'all','companies', 'approved', 'rejected', 'signed'));

        } else if(Auth::user()->role_id == 2) {
            $companies = Company::where('created_by', Auth::id())->get();

            $approved = new Collection();
            $rejected= new Collection();
            $signed= new Collection();
            $companyId = 0;
            $orders=new Collection();

            if($companies) {

                foreach ($companies as $company) {
                    if($company) {
                        $approved=$approved->merge(Order::where('company_id', $company->id)->where('status', 'approved')->get());
                        $rejected=$rejected->merge(Order::where('company_id', $company->id)->where('status', 'rejected')->get());
                        $signed=$signed->merge(Order::where('company_id', $company->id)->where('status', 'signed')->get());
                        $companyId = $company->id;
                    }
                    $order = Order::when($request->status != null, function ($q) use ($request) {
                        return $q->where('status', $request->status);
                    })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                        return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
                    })->when($request->company != null, function ($q) use ($request) {

                        return $q->where('company_id',$request->company);
                    })->where('company_id', $companyId)->orderBy('created_at', 'DESC')->get();
                    $orders=$orders->merge($order) ;

                }
            } else {
                $orders = [];
            }
//dump($rejected);
//dd($orders);



//            if($company) {
//                $approved = Order::where('company_id', $company->id)->where('status', 'approved')->get();
//                $rejected= Order::where('company_id', $company->id)->where('status', 'rejected')->get();
//                $signed= Order::where('company_id', $company->id)->where('status', 'signed')->get();
//                $companyId = $company->id;
//            } else {
//                $approved = [];
//                $rejected= [];
//                $signed= [];
//                $companyId = 0;
//            }
//
//            $orders = Order::when($request->status != null, function ($q) use ($request) {
//                return $q->where('status', $request->status);
//            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
//                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
//            })->when($request->company != null, function ($q) use ($request) {
//
//                return $q->where('company_id',$request->company);
//            })->where('company_id', $companyId)->orderBy('created_at', 'DESC')->get();

//            $checkOrders = Order::where('status', 'new')->where('company_id', $company->id)->get();
//            count($checkOrders) <= 25 ? $length=count($checkOrders): $length=25;
//
//            for($i=0; $i<$length; $i++) {
//
//                $division = Division::where('id', $checkOrders[$i]['division_id'])->first();
//                if ($division) {
//                    $code = base64_encode($division['show_case_id'] . ':pyzPYF7Y5S7Liq@');
//
//
//                    $response = Http::withHeaders([
//                        'Authorization' => 'Basic ' . $code,
//                    ])->get('https://forma.tinkoff.ru/api/partners/v2/orders/' . $checkOrders[$i]['order_id'] . '/info');
//
//                    $result = json_decode($response, true);
//                    if (isset($result['status'])) {
//                        Order::where('id', $checkOrders[$i]['id'])->update(['status' => $result['status']]);
//                    }
//                }
//            }

            return view('main.manager', compact('orders', 'approved','companies', 'rejected', 'signed'));
        } else if(Auth::user()->role_id == 3) {

            $company = Company::where('leader_id', Auth::id())->first();
            $divisions = Division::where('company_id', $company->id)->get();


            $orders = Order::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->division != null, function ($q) use ($request) {

                return $q->where('division_id',$request->division);
            })->where('company_id', $company->id)->orderBy('created_at', 'DESC')->get();
            $approved = Order::where('company_id', $company->id)->where('status', 'approved')->get();
            $rejected = Order::where('company_id', $company->id)->where('status', 'rejected')->get();
            $signed= Order::where('company_id', $company->id)->where('status', 'signed')->get();

//            $checkOrders = Order::where('status', 'new')->where('company_id', $company->id)->get();
//            count($checkOrders) <= 25 ? $length=count($checkOrders): $length=25;
//
//            for($i=0; $i<$length; $i++) {
//
//                $division = Division::where('id', $checkOrders[$i]['division_id'])->first();
//                if ($division) {
//                    $code = base64_encode($division['show_case_id'] . ':pyzPYF7Y5S7Liq@');
//
//
//                    $response = Http::withHeaders([
//                        'Authorization' => 'Basic ' . $code,
//                    ])->get('https://forma.tinkoff.ru/api/partners/v2/orders/' . $checkOrders[$i]['order_id'] . '/info');
//
//                    $result = json_decode($response, true);
//                    if (isset($result['status'])) {
//                        Order::where('id', $checkOrders[$i]['id'])->update(['status' => $result['status']]);
//                    }
//                }
//            }

//    dd($orders);
            return view('main.index', compact('orders', 'divisions', 'approved', 'rejected', 'signed'));
        } else  if(Auth::user()->role_id == 4) {

            $orders = Order::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {

                return $q->where('company_id',$request->company);
            })->where('salesman_id',  Auth::id())->orderBy('created_at', 'DESC')->get();
            $company = UserCompany::where('user_id', Auth::id())->first();
            $divisions = Division::where('company_id', $company->id)->get();
            $approved = Order::where('salesman_id',  Auth::id())->where('status', 'approved')->get();
            $rejected = Order::where('salesman_id',  Auth::id())->where('status', 'rejected')->get();
            $signed= Order::where('salesman_id',  Auth::id())->where('status', 'signed')->get();
            return view('main.index', compact('orders', 'divisions', 'approved', 'rejected', 'signed'));
        }


        else redirect()->route('login');

    }
}
