<?php

namespace App\Http\Controllers\Division;

use App\Http\Controllers\Controller;
use App\Http\Requests\Division\StoreRequest;
use App\Http\Requests\Division\UpdateRequest;
use App\Models\Company;
use App\Models\Division;
use App\Models\DivisionImage;
use App\Models\DivisionInstallment;
use App\Models\LeaderPassword;
use App\Models\Rate;
use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DivisionController extends Controller
{
    public function index()
    {
        if(Auth::user()->role_id == 2) {
            $companies = Company::where('created_by', Auth::id())->get();
            if($companies) {
                $divisions=new Collection();
                foreach ($companies as $company) {
                    $divisions=$divisions->merge(Division::where('company_id', $company->id)->get()) ;
                }

            } else $divisions= [];

        } else $divisions = Division::all();

        $title = 'Торговые точки';
        return view('division.index', compact('divisions', 'title'));
    }
    public function create()
    {
        $rates = Rate::where('type', 'credit')->orderBy('sort')->get();
        $plans = Rate::where('type', 'install_plan')->get();
        $installments = Rate::where('type', 'installment')->get();
        $companies = Company::all();
        $segments = Segment::all();

        return view('division.create', compact('rates', 'companies', 'plans', 'installments', 'segments' ));
    }
    public function show(Division $division)
    {
        $rate = Rate::where('id', $division->rate_id)->first();
        $plan = Rate::where('id', $division->plan_id)->first();
        $company = Company::where('id', $division->company_id)->first();
        $images = DivisionImage::where('division_id', $division->id)->get();
        $segments = Segment::all();

        return view('division.show', compact('division', 'rate', 'company', 'images', 'plan'));
    }
    public function store(StoreRequest $request)
    {

        $data = $request->validated();

        $images = $request->file('images');
        if(isset($data['installments'])) {
            $installmentsIds = $data['installments'];
            unset( $data['installments']);
        }
//        dd($images);
        unset($data['images']);
        $division = Division::firstOrCreate($data);
//dd($data);

        if(isset( $images)) {
            foreach ($images as $imagefile) {
                $image = new DivisionImage();
                $path = Storage::disk('public')->put('/images/divisions' , $imagefile );
                $image->url = $path;
                $image->division_id = $division->id;
                $image->save();
            }

        }

            if(isset($installmentsIds)) {
                foreach ($installmentsIds as $tagId) {
                    $division->installments()->attach($tagId);

                }
            }



       return redirect()->route('division.index');



    }

    public function edit(Division $division)
    {
        $rates = Rate::where('type', 'credit')->orderBy('sort')->get();
        $plans = Rate::where('type', 'install_plan')->get();
        $installments = Rate::where('type', 'installment')->get();
        $segments = Segment::all();

        $divisionInstallments = DivisionInstallment::where('division_id',$division->id )->get();
        foreach ($plans as $plan) {
            foreach ($divisionInstallments as $item) {
                if($plan['id']==$item['installment_id']) {
                    $plan['selected'] =true;
                }
            }
        }

        if(Auth::user()->role_id == 2) {
            $companies= Company::where('created_by', Auth::id())->get();
        } else $companies = Company::all();
        $images = DivisionImage::where('division_id', $division->id)->get();

        return view('division.edit', compact('division', 'rates', 'companies', 'images', 'plans', 'installments', 'segments'));
    }

    public function update(UpdateRequest $request, Division $division)
    {
        if(isset($_POST['delete']))
        {
            $image = DivisionImage::where('id', $request->delete)->first();
            if(Storage::delete($image->url)) {
                $image->delete();
                return back()->with('flash_message_success', 'Изображение удалено');
            }

            return back()->with('flash_message_error', 'Изображение не удалено');
        }
        else if(isset($_POST['update']))
        {
//            $data = array($request);
            $data = $request->validated();
            if(!isset($data['find_credit'])){
                $data['find_credit'] = null;
            }
            if(!isset($data['hide_find_credit'])){
                $data['hide_find_credit'] = null;
            }
            if(!isset($data['find_mfo'])){
                $data['find_mfo'] = null;
            }
            if(!isset($data['hide_find_mfo'])){
                $data['hide_find_mfo'] = null;
            }
//            dd($data);
            $images = $request->file('images');


            if(isset( $images)) {
                unset($data['images']);
                foreach ($images as $imagefile) {
                    $image = new DivisionImage();
                    $path = Storage::disk('public')->put('/images/divisions' , $imagefile );
                    $image->url = $path;
                    $image->division_id = $division->id;
                    $image->save();
                }

            }
            if(isset($data['installments'])) {
                $installmentsIds = $data['installments'];
                if(isset($installmentsIds)) {
                    unset($data['installments']);
                    $division->installments()->sync($installmentsIds);

                }
            }


            if($division->update($data)) {
                return redirect()->route('division.index')->with('flash_message_success', 'Данные обновились!');
            } else return back()->with('flash_message_error', 'Данные не обновились!');
        }



    }

    public function delete(Division $division)
    {
$divisionInstallments = DivisionInstallment::where('division_id', $division->id)->get();
        if($divisionInstallments) {
            foreach ($divisionInstallments as $item) {
                $item->delete();
            }
        }
        $division->delete();
      $images = DivisionImage::where('division_id', $division->id)->get();

      foreach ($images as $image) {

          if(Storage::delete($image->url)) {
              $image->delete();
          }

      }

        return redirect()->route('division.index');
    }


}
