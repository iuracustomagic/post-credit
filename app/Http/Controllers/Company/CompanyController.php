<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Models\Company;
use App\Models\CompanyImages;
use App\Models\Division;
use App\Models\DivisionImage;
use App\Models\LeaderPassword;
use App\Models\Rate;
use App\Models\Role;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\UserDivision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class CompanyController extends Controller
{
    public function index()
    {
        if(Auth::user()->role_id == 2) {
            $companies= Company::where('created_by', Auth::id())->get();
        } else if(Auth::user()->role_id == 1) {
            $companies = Company::all();
        } else $companies =[];


        $title = 'Юридические лица';
        return view('company.index', compact('companies', 'title'));
    }

    public function newList()
    {
        $companies = Company::where('status', 1)->get();
        $title = 'Юридические лица';
        return view('company.index', compact('companies', 'title'));
    }

    public function create()
    {

        return view('company.create');
    }
    public function show(Company $company)
    {

        $user = User::where('id', $company->leader_id)->first();
        $images = CompanyImages::where('company_id', $company->id)->get();
        $passwordDate = LeaderPassword::where('id',$user->password_id )->first();
        return view('company.show', compact('company', 'user', 'passwordDate', 'images'));
    }

    public function showDivisions(Company $company)
    {

        $divisions = Division::where('company_id', $company->id)->get();

//        dd($divisions);
        return view('company.showDivisions', compact('company', 'divisions'));
    }
    public function addDivision(Company $company)
    {
        $rates = Rate::where('type', 'credit')->get();
        $company = Company::where('id', $company->id)->first();

        return view('company.addDivision', compact('rates', 'company' ));
    }

    public function showSalesmen(Company $company)
    {

        $userCompanies = UserCompany::where('company_id', $company->id)->get();

        $users = [];
        if($userCompanies) {
            foreach($userCompanies as $item) {
                $user = User::where('id', $item->user_id)->first();
                $users[] =$user;
            }

        }
//   dd($users);
//        dd($divisions);
        $title = 'Продавцы';

        return view('user.salesman.index', compact('users', 'title'));
    }
    public function store(StoreRequest $request)
    {
        $random_number = rand(1000, 9999);

        if (isset($_POST['download'])) {
            $data = $request->validated();

            $created_at = now()->format('d-m-y');

            $futureDate = date('d-m-y', strtotime('+1 year'));

            $templateProcessor = new TemplateProcessor('word-template/company.docx');
            $templateProcessor->setValue('date', $created_at);
            $templateProcessor->setValue('org_name', $data['name']);
            $templateProcessor->setValue('bank_name', $data['bank_name']);
            $templateProcessor->setValue('bik', $data['bik']);
            $templateProcessor->setValue('address', $data['address']);
            $templateProcessor->setValue('first_name', $data['first_name']);
            $templateProcessor->setValue('last_name', $data['last_name']);
            $templateProcessor->setValue('surname', $data['surname']);
            $templateProcessor->setValue('email', $data['email']);
            $templateProcessor->setValue('phone', $data['phone']);
            $templateProcessor->setValue('inn', $data['inn']);
            $templateProcessor->setValue('ogrn', $data['ogrn']);
            $templateProcessor->setValue('checking_account', $data['checking_account']);
            $templateProcessor->setValue('correspond_account', $data['correspond_account']);
            $templateProcessor->setValue('till', $futureDate);
            $templateProcessor->setValue('doc_number', $random_number);
            $templateProcessor->setValue('f_s', mb_substr($data['first_name'], 0, 1));
            $templateProcessor->setValue('s_s',mb_substr($data['surname'], 0, 1));

            $fileName = $data['name'];
            $templateProcessor->saveAs($fileName.'.docx');
            return response()->download($fileName.'.docx')->deleteFileAfterSend(true);

        }
        else if(isset($_POST['save'])) {
            $data = $request->validated();
//dd($data);
            $password_date = [
                'number' => $data['number'],
                'by'=> $data['by'],
                'date'=>$data['date'],
                'registration'=>$data['registration']
            ];
            $images = $request->file('images');



            $password = LeaderPassword::firstOrCreate($password_date);


            $user_date = [
                'first_name' => $data['first_name'],
                'last_name'=> $data['last_name'],
                'surname'=>$data['surname'],
                'login'=>$data['login'],
                'email'=>$data['email'],
                'password'=>$data['password'],
                'phone'=>$data['phone'],
                'role_id'=>$data['role_id'],
                'password_id'=> $password['id'],
            ];
//            dd($data);

            $userResponse = User::firstOrCreate( $user_date);


            if($userResponse) {
                $organisation_date['leader_id'] = $userResponse['id'];

                $organisation_date = [
                    'status' => 1,
                    'name' => $data['name'],
                    'created_by' => $data['created_by'],
                    'inn'=> $data['inn'],
                    'ogrn'=>$data['ogrn'],
                    'address'=>$data['address'],
                    'checking_account'=>$data['checking_account'],
                    'bank_name'=>$data['bank_name'],
                    'correspond_account'=>$data['correspond_account'],
                    'bik'=>$data['bik'],
                    'leader_id'=>$userResponse['id'],
                    'doc_number'=>$random_number,

                ];
                $company = Company::firstOrCreate( $organisation_date);
                if($company) {

                    if(isset($images)) {
                        $allowedfileExtension=['pdf','jpg', 'jpeg', 'png','docx'];

                        foreach ($images as $imagefile) {

                            $extension = $imagefile->getClientOriginalExtension();
                            $check=in_array($extension,$allowedfileExtension);

                            if($check) {
                                $image = new CompanyImages();
                                $path = Storage::disk('public')->put('/files/company' , $imagefile );
                                $image->url = $path;
                                $image->type = $extension;
                                $image->company_id = $company->id;
                                $image->save();
                            } else return redirect()->route('company.edit', $company->id)->with('flash_message_error', 'Заружайте файлы в формате png, pdf, jpg , doc');

                        }

                    }
                    return redirect()->route('company.index')->with('flash_message_success', 'Данные успешно сохранились!');
                } else {
                    return back()->with('flash_message_error', 'Данные не сохранились!');
                }

            } else {
                return back()->with('flash_message_error', 'Данные не сохранились!');
            }

        }


    }

    public function edit(Company $company)
    {
        $user = User::where('id', $company->leader_id)->first();
        $passwordDate = LeaderPassword::where('id',$user->password_id )->first();
        $images = CompanyImages::where('company_id', $company->id)->get();

        return view('company.edit', compact('company', 'user', 'passwordDate', 'images'));
    }

    public function update(UpdateRequest $request, Company $company)
    {
        $data = $request->validated();
//        dd($data);
        if(isset($_POST['delete']))
        {
            $image = CompanyImages::where('id', $request->delete)->first();

          if(Storage::delete($image->url)) {
              $image->delete();
              return back()->with('flash_message_success', 'Изображение удалено');
          }

            return back()->with('flash_message_error', 'Изображение не удалено');
        }
        else if (isset($_POST['downloadFile'])) {
            $file = CompanyImages::where('id', $request->downloadFile)->first();
            $name = str_replace('files/company/', '',$file->url );
            $destination = public_path('storage/');
            $path = public_path('storage/').$file->url;
            if (file_exists($path)) {
                return response()->download($path);
            }
            return Storage::download($file->url);
        }
        else if (isset($_POST['download'])) {

            $random_number = rand(1000, 9999);
            $created_at = date_format($company['created_at'], 'd-m-y');

            $futureDate = date('d-m-y', strtotime('+1 year', strtotime($company['created_at'])));

            $templateProcessor = new TemplateProcessor('word-template/company.docx');
            $templateProcessor->setValue('date', $created_at);
            $templateProcessor->setValue('org_name', $data['name']);
            $templateProcessor->setValue('bank_name', $data['bank_name']);
            $templateProcessor->setValue('bik', $data['bik']);
            $templateProcessor->setValue('address', $data['address']);
            $templateProcessor->setValue('first_name', $data['first_name']);
            $templateProcessor->setValue('last_name', $data['last_name']);
            $templateProcessor->setValue('surname', $data['surname']);
            $templateProcessor->setValue('email', $data['email']);
            $templateProcessor->setValue('phone', $data['phone']);
            $templateProcessor->setValue('inn', $data['inn']);
            $templateProcessor->setValue('ogrn', $data['ogrn']);
            $templateProcessor->setValue('checking_account', $data['checking_account']);
            $templateProcessor->setValue('correspond_account', $data['correspond_account']);
            $templateProcessor->setValue('till', $futureDate);
            $templateProcessor->setValue('doc_number', $random_number);
            $templateProcessor->setValue('f_s', mb_substr($data['first_name'], 0, 1));
            $templateProcessor->setValue('s_s',mb_substr($data['surname'], 0, 1));

            $fileName = $data['name'];
            $templateProcessor->saveAs($fileName.'.docx');
            return response()->download($fileName.'.docx')->deleteFileAfterSend(true);

        }
        else if(isset($_POST['update']))

        {
//            $data = $request->validated();

            $images = $request->file('images');


            $password_date = [
                'number' => $data['number'],
                'by'=> $data['by'],
                'date'=>$data['date'],
                'registration'=>$data['registration']
            ];

            $password = User::where('id',$company->leader_id )->first();

            LeaderPassword::where('id', $password->password_id)->update($password_date);

            if($data['password'] == '') {
                $user_date = [
                    'first_name' => $data['first_name'],
                    'last_name'=> $data['last_name'],
                    'surname'=>$data['surname'],
                    'login'=>$data['login'],
                    'email'=>$data['email'],
                    'phone'=>$data['phone'],

                ];
            } else {
                $user_date = [
                    'first_name' => $data['first_name'],
                    'last_name'=> $data['last_name'],
                    'surname'=>$data['surname'],
                    'login'=>$data['login'],
                    'email'=>$data['email'],
                    'password'=>Hash::make($data['password']) ,
                    'phone'=>$data['phone'],

                ];
            }

//        dd($user_date);
            $userResponse =  User::where('id',$company->leader_id )->update( $user_date);

            if(isset($images)) {
                $allowedfileExtension=['pdf','jpg', 'jpeg', 'png','docx'];

                foreach ($images as $imagefile) {

                    $extension = $imagefile->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);

                    if($check) {
                        $image = new CompanyImages();
                        $path = Storage::disk('public')->put('/files/company' , $imagefile );
                        $image->url = $path;
                        $image->type = $extension;
                        $image->company_id = $company->id;
                        $image->save();
                    } else return redirect()->route('company.edit', $company->id)->with('flash_message_error', 'Заружайте файлы в формате png, pdf, jpg , doc');

                }

            }


            if($userResponse) {
                $organisation_date = [
                    'status' => $data['status'],
                    'name' => $data['name'],
                    'inn'=> $data['inn'],
                    'ogrn'=>$data['ogrn'],
                    'address'=>$data['address'],
                    'checking_account'=>$data['checking_account'],
                    'bank_name'=>$data['bank_name'],
                    'correspond_account'=>$data['correspond_account'],
                    'bik'=>$data['bik'],

                ];

//            dd($organisation_date);
                if($company->update( $organisation_date)) {
                    return back()->with('flash_message_success', 'Данные успешно обновились!');
                } else {
                    return back()->with('flash_message_error', 'Данные не обновились!');
                }

            } else {
                return back()->with('flash_message_error', 'Данные не обновились!');
            }

        }



    }

    public function delete(Company $company)
    {
        $user = User::where('id', $company->leader_id)->first();
        $divisions = Division::where('company_id', $company->id)->get();

        if($divisions) {
            foreach ($divisions as $division) {
                $userDivisions = UserDivision::where('division_id', $division->id)->get();
                foreach($userDivisions as $userDivision) {
                    $userDivision->delete();
                }
                $division->delete();
            }
        }
        if($user) {
            $leaderPassword = LeaderPassword::where('id', $user->password_id)->first();
            if($leaderPassword) {
                $leaderPassword->delete();
            }
        }
        $userCompanies = UserCompany::where('company_id',$company->id )->get();
        foreach ($userCompanies as $userCompany) {
            $userCompany->delete();
        }

        $company->delete();
        $user->delete();



        $images = CompanyImages::where('company_id', $company->id)->get();

        foreach ($images as $image) {

            if(Storage::delete($image->url)) {
                $image->delete();
            }

        }

        return redirect()->route('company.index');
    }
}
