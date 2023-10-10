@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('salesman.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Создать продавца</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    @include('components.flash_message')
        <!-- Main content -->

        <section class="content">
            <div class="container-fluid ml-4">
                <div class="row w-75">
                    <form action="{{route('salesman.store')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" class="form-control" name="role_id" value="4" >
                        <input type="hidden" class="form-control" name="created_by" value="{{\Illuminate\Support\Facades\Auth::id()}}" >
                        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 2)
                        <input type="hidden" class="form-control" name="manager_id" value="{{\Illuminate\Support\Facades\Auth::id()}}" >
                        @endif
                        <div class="row  ">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" name="last_name"  onblur = "myBlurFunction(this)" placeholder="Фамилия" value="{{old('last_name')}}">
                                @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" name="first_name" placeholder="Имя" value="{{old('first_name')}}">
                                @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>

                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" name="surname" placeholder="Отчество" value="{{old('surname')}}">
                                @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" name="phone" placeholder="Телефон" value="{{old('phone')}}">
                                @error('phone')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" id="login" name="login" placeholder="Логин" value="{{old('login')}}">
                                @error('login')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                                @error('password')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input class="btn btn-outline-success" id="copyBtn" style="display: none" onclick="handleCopyLoginBtn()" value="Скопировать" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="exampleSelectBorder">Статус</label>
                                <select class="custom-select form-control" name="status" id="exampleSelectBorder" >
                                    <option value="1" selected>Активный</option>
                                    <option value="0">Не активный</option>

                                </select>
                            </div>
                        </div>
                        <div class="row " style="margin-bottom: 150px">
                            <div class="form-group col-sm-4" data-select2-id="29">
                                <label>Выберите ООО</label>
                                <select name="company_id" id="companySelect" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="" tabindex="-1" aria-hidden="true">

                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col-sm-4" data-select2-id="69">
                                <label>Привязать к ТТ</label>
                                <select name="division_id" id="divisionSelect" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="" tabindex="-1" aria-hidden="true">

{{--                                    @foreach($divisions as $division)--}}
{{--                                        <option data-select2-id="{{$division->id}}" value="{{$division->id}}">{{$division->name}}</option>--}}
{{--                                    @endforeach--}}

                                </select>

                          </div>

                        </div>
                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-primary" value="Сохранить">
                        </div>
                    </form>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
@endsection

        @push('script')
            <script>
                const firstName = document.getElementById('first_name')
                const login = document.getElementById('login')
                const password = document.getElementById('password')
                const copyBtn =document.getElementById('copyBtn');
                const companySelect =document.getElementById('companySelect');
                const divisionSelect =document.getElementById('divisionSelect');

                function selectCompany() {
                    let companyId = $('#companySelect').find(":selected").val();
                    console.log(companyId)
                    let companies = {!! json_encode($companies) !!};
                    console.log(companies);
                    let CompanyList = companies.find(company => company.id === Number(companyId))
                    const DivisionList = CompanyList.divisions
                    console.log(DivisionList)
                    if(DivisionList) {
                        let options = "";
                        //идем по списку девайсов и на каждом создаем очередной option с соответствующими значениями
                        DivisionList.forEach(division=> {
                            console.log(division.id)
                            options += `<option value="${division.id}" >${division.name}</option>`;
                        })

                        divisionSelect.innerHTML = options;

                    }
                }
                selectCompany()


                $('#companySelect').on('change', event=>{
                    selectCompany()

                })

                function myBlurFunction(input) {

                    const nameValue = input.value.toLowerCase() + generateRandomString(2)

                    login.value = transliter(nameValue)
                    password.value = generateRandomString(5, true)
                    copyBtn.style.display = 'block';

                }
                $('.select2').select2()

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                function handleCopyLoginBtn() {
                    const loginValue = login.value
                    const passwordValue = password.value
                    const copyValue = `логин: ${loginValue}, пароль: ${passwordValue}`;

                    const area = document.createElement('textarea');
                    document.body.appendChild(area);
                    area.value = copyValue;
                    area.select();
                    document.execCommand("copy");
                    document.body.removeChild(area);
                    Toast.fire({
                        icon: 'info',
                        title: 'Логин пароль скопированы.'
                    })

                }




            </script>
    @endpush
