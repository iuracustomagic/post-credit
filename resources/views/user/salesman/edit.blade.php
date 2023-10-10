@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div>
                        <a href="{{route('salesman.index')}}" class="btn btn-secondary" >
                            <i class="fas fa-backward mr-2"></i> Назад
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <h1 class="m-0">Редактировать продавца</h1>
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
                    <form action="{{route('salesman.update', $user->id)}}" method="post" class="w-100">
                        @csrf
                        @method('patch')
                        <div class="row  ">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Фамилия">
                                @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" placeholder="Имя">
                                @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>

                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->surname}}" name="surname" placeholder="Отчество">
                                @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->phone}}" name="phone" placeholder="Телефон">
                                @error('phone')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>

                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->login}}" name="login" placeholder="Логин">
                                @error('login')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="password" class="form-control" name="password" placeholder="Пароль">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="exampleSelectBorder">Статус</label>
                                <select class="custom-select form-control" name="status" id="exampleSelectBorder" >
                                    <option {{$user->status == '1' ? 'selected' : ''}} value="1">Активный</option>
                                    <option {{$user->status == '0' ? 'selected' : ''}} value="0">Не активный</option>

                                </select>
                            </div>
                        </div>

                        <div class="row " style="margin-bottom: 150px">
                            <div class="form-group col-sm-4" data-select2-id="{{$company_id}}">
                                <label>Выберите ООО</label>
                                <select name="company_id" id="companySelect" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="{{$company_id}}" tabindex="-1" aria-hidden="true">

                                    @foreach($companies as $company)

                                        <option {{$company->id == $company_id ? 'selected' : ''}} data-select2-id="{{$company->id}}" value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col-sm-4" data-select2-id="">
                                <label>Привязать к ТТ</label>
                                <select name="division_id" id="divisionSelect" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="{{$division_id}}" tabindex="-1" aria-hidden="true">
{{--                                    <option selected value="{{$divisionOld->id}}">{{$divisionOld->name}}</option>--}}
                                    @foreach($divisionOld as $division)
                                        <option  data-select2-id="{{$division_id}}" value="{{$division->id}}">{{$division->name}}</option>
                                    @endforeach

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

                $('.select2').select2()

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
                const divisionSelect =document.getElementById('divisionSelect');


                $('#companySelect').on('change', event=>{
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

                })


            </script>
    @endpush
