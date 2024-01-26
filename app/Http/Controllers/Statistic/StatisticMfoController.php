<?php

namespace App\Http\Controllers\Statistic;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Division;
use App\Models\MfoOrder;
use App\Models\UserDivision;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StatisticMfoController extends Controller
{
    public function index(Request $request)
    {
        if(Auth::user()->role_id == 1) {
            $companies = Company::all();
            $all = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->get();
            $approved = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->where('status', 'approved')->get();
            $rejected= MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->where('status', 'declined')->get();
            $signed= MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->where('status', 'signed')->get();

            $orders = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->when($request->status == null && $request->company == null && $request->date_from == null && $request->date_to == null, function ($q) use ($request) {
                return $q->take(100);
            })->orderBy('created_at', 'DESC')->get();
//dd($orders);


            return view('statisticMfo.admin', compact('orders', 'all','companies', 'approved', 'rejected', 'signed'));

        }
        else if(Auth::user()->role_id == 2) {
            $companies = Company::where('created_by', Auth::id())->get();

            $approved = new Collection();
            $rejected= new Collection();
            $signed= new Collection();
            $companyId = 0;
            $orders=new Collection();

            if($companies) {

                foreach ($companies as $company) {
                    if($company) {
                        $approved=$approved->merge(MfoOrder::when($request->status != null, function ($q) use ($request) {
                            return $q->where('status', $request->status);
                        })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
                        })->when($request->company != null, function ($q) use ($request) {
                            return $q->where('company_id',$request->company);
                        })->where('company_id', $company->id)->where('status', 'approved')->get());
                        $rejected=$rejected->merge(MfoOrder::when($request->status != null, function ($q) use ($request) {
                            return $q->where('status', $request->status);
                        })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
                        })->when($request->company != null, function ($q) use ($request) {
                            return $q->where('company_id',$request->company);
                        })->where('company_id', $company->id)->where('status', 'declined')->get());
                        $signed=$signed->merge(MfoOrder::when($request->status != null, function ($q) use ($request) {
                            return $q->where('status', $request->status);
                        })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                            return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
                        })->when($request->company != null, function ($q) use ($request) {
                            return $q->where('company_id',$request->company);
                        })->where('company_id', $company->id)->where('status', 'signed')->get());
                        $companyId = $company->id;
                    }
                    $order = MfoOrder::when($request->status != null, function ($q) use ($request) {
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





            return view('statisticMfo.manager', compact('orders', 'approved','companies', 'rejected', 'signed'));
        }
        else if(Auth::user()->role_id == 3) {

            $company = Company::where('leader_id', Auth::id())->first();
            $divisions = Division::where('company_id', $company->id)->get();


            $orders = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->division != null, function ($q) use ($request) {
                return $q->where('division_id',$request->division);
            })->where('company_id', $company->id)->orderBy('created_at', 'DESC')->get();
            $approved = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->division != null, function ($q) use ($request) {
                return $q->where('division_id',$request->division);
            })->where('company_id', $company->id)->where('status', 'approved')->get();
            $rejected = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->division != null, function ($q) use ($request) {
                return $q->where('division_id',$request->division);
            })->where('company_id', $company->id)->where('status', 'declined')->get();
            $signed= MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->division != null, function ($q) use ($request) {
                return $q->where('division_id',$request->division);
            })->where('company_id', $company->id)->where('status', 'signed')->get();



//    dd($orders);
            return view('statisticMfo.index', compact('orders', 'divisions', 'approved', 'rejected', 'signed'));
        }
        else  if(Auth::user()->role_id == 4) {

            $orders = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->division != null, function ($q) use ($request) {
                return $q->where('division_id',$request->division);
            })->where('salesman_id',  Auth::id())->orderBy('created_at', 'DESC')->get();
            $division = UserDivision::where('user_id', Auth::id())->first();

            $divisions = Division::where('id', $division->division_id)->get();
            $approved = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->where('salesman_id',  Auth::id())->where('status', 'approved')->get();
            $rejected = MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->where('salesman_id',  Auth::id())->where('status', 'declined')->get();
            $signed= MfoOrder::when($request->status != null, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })->when($request->date_from != null && $request->date_to != null, function ($q) use ($request) {
                return $q->whereBetween('created_at', [$request->date_from, $request->date_to]);
            })->when($request->company != null, function ($q) use ($request) {
                return $q->where('company_id',$request->company);
            })->where('salesman_id',  Auth::id())->where('status', 'signed')->get();
            return view('statisticMfo.index', compact('orders', 'divisions', 'approved', 'rejected', 'signed'));
        }


        else redirect()->route('login');
    }
}
