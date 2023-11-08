@php
    use Illuminate\Support\Facades\Auth;

    $currentUser = Auth::user();
@endphp

@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('company.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <div class="row mb-2 mt-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Создать юр. лицо</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    @include('components.flash_message')

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4 ">

                <div class="row mt-4 ">
                    <form action="{{route('company.store')}}" method="post" class="w-100" enctype="multipart/form-data">
                        @csrf
                        <div class="w-75 d-flex justify-content-end">
                            <div class=" fs-5 mr-4">
                                <span class="mr-2">Статус:</span>
                                <span class="text-primary">Новая</span>
                            </div>
                            @if($currentUser->role_id ==1)
                            <div class=" fs-5 d-flex">
                                <label class="mr-2">Создана:</label>
                                <select name="created_by" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="0" tabindex="-1" aria-hidden="true">
                                    <option  value="{{$currentUser->id}}">Администратором</option>
                                    @foreach($managers as $manager)
                                        <option value="{{$manager->id}}">{{$manager->first_name.' '.$manager->last_name}}</option>
                                    @endforeach

                                </select>

                            </div>
                            @else
                                <input type="hidden" class="form-control" name="created_by" value="{{$currentUser->id}}" >
                                @endif
                        </div>

                        <input type="hidden" class="form-control" name="status" value="1" >
                        <input type="hidden" class="form-control" name="role_id" value="3" >
                    <nav class="w-100">
                        <div class="nav nav-tabs" id="product-tab" role="tablist">
                            <a class="nav-item nav-link active fs-5" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">
                                Данные руководителя
                            </a>
                            <a class="nav-item nav-link fs-5" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">
                                Данные организации
                            </a>
                            <a class="nav-item nav-link fs-5" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">
                                Загрузки
                            </a>
                        </div>
                    </nav>
                    <div class="tab-content p-3 " style="min-height: 500px" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                            <div class="row w-75 mt-3">

                                    <div class="row  ">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" id="last_name" name="last_name" onblur = "myBlurFunction(this)" placeholder="Фамилия" value="{{old('last_name')}}">
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
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="email" placeholder="Почта" value="{{old('email')}}">
                                            @error('email')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>

                                    <h3>Паспорт</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="number" placeholder="Серия\номер" value="{{old('number')}}">
                                            @error('number')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="by" placeholder="Кем выдан" value="{{old('by')}}">
                                            @error('by')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <input type="date" class="form-control" name="date" placeholder="Дата выдачи" value="{{old('date')}}">
                                            @error('date')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="registration" placeholder="Прописка" value="{{old('registration')}}">
                                            @error('registration')<p class="text-danger"> {{$message}}</p>@enderror
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
                                            <input class="btn btn-outline-success" id="copyBtn" style="display: none" onclick="handleCopyBtn()" value="Скопировать" />
                                        </div>
                                    </div>



                            </div>
                        </div>

                      <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                          <div class="row w-75 mt-3">

                                  <div class="row  ">
                                      <div class="form-group col-sm-4">
                                          <input type="text" class="form-control" name="name" placeholder="Наименование организации" value="{{old('name')}}">
                                          @error('name')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                      <div class="form-group col-sm-4">
                                          <input type="text" class="form-control" name="inn" placeholder="ИНН" value="{{old('inn')}}">
                                          @error('inn')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>

                                      <div class="form-group col-sm-4">
                                          <input type="text" class="form-control" name="ogrn" placeholder="ОГРН" value="{{old('ogrn')}}">
                                          @error('ogrn')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                  </div>
                                  <div class="row  ">
                                      <div class="form-group col-sm-12">
                                          <input type="text" class="form-control" name="address" placeholder="Адресс" value="{{old('address')}}">
                                          @error('address')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                  </div>
                                  <div class="row  ">
                                      <div class="form-group col-sm-6">
                                          <input type="text" class="form-control" name="checking_account" placeholder="Расчетный счет" value="{{old('checking_account')}}">
                                          @error('checking_account')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                      <div class="form-group col-sm-6">
                                          <input type="text" class="form-control" name="bank_name" placeholder="Наименование банка" value="{{old('bank_name')}}">
                                          @error('bank_name')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                  </div>
                                  <div class="row  ">
                                      <div class="form-group col-sm-6">
                                          <input type="text" class="form-control" name="correspond_account" placeholder="Кореспонденский счет" value="{{old('correspond_account')}}">
                                          @error('correspond_account')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                      <div class="form-group col-sm-6">
                                          <input type="text" class="form-control" name="bik" placeholder="БИК" value="{{old('bik')}}">
                                          @error('bik')<p class="text-danger"> {{$message}}</p>@enderror
                                      </div>
                                  </div>
                              <div class="my-3 d-flex justify-content-end">
                                  <button type="submit" name="download" class="btn btn-success">Скачать договор</button>
                              </div>
                          </div>

                          </div>
                        <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">

                            <div class="row mb-3">
                                <div class="form-group col-sm-4 ">
                                    <label  class="col-form-label" for="exampleInputFile">Загрузка документов</label>
                                    <div class="input-group ">
                                        <div class="custom-file">
                                            <input type="file" name="images[]" class="form-control" id="exampleInputFile" multiple="multiple">
                                        </div>
                                    </div>
                                    @error('images')<p class="text-danger"> {{$message}}</p>@enderror

                                </div>
                                <div class="col-sm-8 ml-3 d-flex flex-wrap" id="imageContainer">

                                </div>


                            </div>

                        </div>

                    </div>

                        <div class="form-group text-center">
{{--                            <a href="{{route('company.index')}}" class="btn btn-success mr-4" style="width: 200px" >Отправить на проверку </a>--}}
{{--                            <input type="submit" class="btn btn-primary mr-4" value="Сохранить" style="width: 200px">--}}
                            <input type="submit" name="save" class="btn btn-primary mr-4" id="submitBtn" value="Отправить на проверку" disabled style="width: 200px">
                            <a href="{{route('company.index')}}" class="btn btn-danger" style="width: 200px">Закрыть </a>
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
                const selectImage =  document.getElementById('exampleInputFile');
                const copyBtn =document.getElementById('copyBtn');
                const submitBtn =document.getElementById('submitBtn');
                const imageContainer = document.getElementById('imageContainer');

                const allInputs = document.querySelectorAll('input[type="text"]')

                $('.select2').select2()

                for (let i = 0; i < allInputs.length; i++) {
                    allInputs[i].addEventListener("change", function() {
                        allInputs.forEach(input => {
                            if(input.value == "" ) {
                                submitBtn.disabled=true;
                            }
                            else {
                                submitBtn.disabled=false;
                            }
                        })


                    });
                }


                function handleCopyBtn() {
                    const loginValue = login.value
                    const passwordValue = password.value
                    const copyValue = `логин: ${loginValue}, пароль: ${passwordValue}`;
                    console.log(passwordValue)
                    console.log(password.value)

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

                function myBlurFunction(input) {
                    console.log(input.value)
                    const nameValue = input.value.toLowerCase() + generateRandomString(2)

                     login.value = transliter(nameValue)
                    password.value = generateRandomString(6)
                    copyBtn.style.display = 'block';
                }


                let imagesArray = []

                selectImage.onchange = evt => {
                    const imgs =document.querySelectorAll('.added_image')
                    if( imgs ) {

                        imgs.forEach(img =>{
                            img.remove()
                        })

                    }
                    const files = selectImage.files

                    for (let i = 0; i < files.length; i++) {
                        if(files[i].type === 'image/jpeg') {
                            imagesArray.push(files[i])
                            if (files[i]) {
                                const image = document.createElement("img");
                                image.src = URL.createObjectURL(files[i]);
                                image.alt = "Division image";
                                image.classList.add('added_image');
                                image.style.width = "300px";
                                image.style.marginBottom = "20px";
                                image.style.marginRight = "20px";
                                imageContainer.append(image);
                            }
                        }
                    }

                }


            </script>
    @endpush
