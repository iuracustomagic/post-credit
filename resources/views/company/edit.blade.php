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
            <div class="container-fluid position-relative">
                <div>
                    <a href="{{route('company.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <div class="row mb-2 mt-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{$company->name}}</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    @include('components.flash_message')
{{--    @include('components.creatingWord')--}}

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4 ">
                <div class="main_view" onclick="this.style.display ='none'">
                    <img src="" id="main" alt="IMAGE">
                </div>

                <div class="row mt-4 ">
                    <form action="{{route('company.update', $company->id)}}" method="post" class="w-100" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <input type="hidden" class="form-control" name="role_id" value="3" >
                       <div class="row" style="width: 90%">
                        <nav class="col-sm-8 w-100">
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
                        <div class="col-sm-4 d-flex justify-content-end">
                            <div class=" fs-4 d-flex">
                                <span class="mr-2">Статус:</span>
                                @if($currentUser->status == 1)
                                    <select  class="custom-select form-control ml-2" name="status" id="">
                                        <option {{$company->status == 1 ? 'selected' : ''}} value="1">Новая</option>

                                        <option {{$company->status == 2 ? 'selected' : ''}} value="2">Активная</option>
                                        <option {{$company->status == 3 ? 'selected' : ''}} value="3">Отключена</option>
                                    </select>

                                @else
                                <span class="text-primary">{{$company->statusTitle}}</span>
                                 @endif
                            </div>

                        </div>
                       </div>
                        <div class="tab-content py-3 " style="min-height: 500px" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                <div class="row w-75 mt-3">

                                    <div class="row  ">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" id="last_name" name="last_name"  placeholder="Фамилия" value="{{$user->last_name }}">
                                            @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="first_name" placeholder="Имя" value="{{$user->first_name}}">
                                            @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="surname" placeholder="Отчество" value="{{$user->surname}}">
                                            @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="phone" placeholder="Телефон" value="{{$user->phone}}">
                                            @error('phone')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="email" placeholder="Почта" value="{{$user->email}}">
                                            @error('email')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>

                                    <h3>Паспорт</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="number" placeholder="Серия\номер" value="{{$passwordDate->number}}">
                                            @error('number')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="by" placeholder="Кем выдан" value="{{$passwordDate->by}}">
                                            @error('by')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <input type="date" class="form-control" name="date" placeholder="Дата выдачи" value="{{$passwordDate->date}}">
                                            @error('date')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" id="login" name="login" placeholder="Логин" value="{{$user->login}}">
                                            @error('login')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                                            @error('password')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>


                                    <div class="row mb-5">
                                        <div class="form-group col-sm-8">
                                            <input type="text" class="form-control" name="registration" placeholder="Прописка" value="{{$passwordDate->registration}}">
                                            @error('registration')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>

                                    </div>



                                </div>
                            </div>

                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                <div class="row w-75 mt-3">

                                    <div class="row  ">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="name" placeholder="Наименование организации" value="{{$company->name}}">
                                            @error('name')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="inn" placeholder="ИНН" value="{{$company->inn}}">
                                            @error('inn')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="ogrn" placeholder="ОГРН" value="{{$company->ogrn}}">
                                            @error('ogrn')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="row  ">
                                        <div class="form-group col-sm-12">
                                            <input type="text" class="form-control" name="address" placeholder="Адресс" value="{{$company->address}}">
                                            @error('address')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="row  ">
                                        <div class="form-group col-sm-6">
                                            <input type="text" class="form-control" name="checking_account" placeholder="Расчетный счет" value="{{$company->checking_account}}">
                                            @error('checking_account')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input type="text" class="form-control" name="bank_name" placeholder="Наименование банка" value="{{$company->bank_name}}">
                                            @error('bank_name')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="row  ">
                                        <div class="form-group col-sm-6">
                                            <input type="text" class="form-control" name="correspond_account" placeholder="Кореспонденский счет" value="{{$company->correspond_account}}">
                                            @error('correspond_account')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <input type="text" class="form-control" name="bik" placeholder="БИК" value="{{$company->bik}}">
                                            @error('bik')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="my-3 d-flex justify-content-end">
                                        <button type="submit" name="download" class="btn btn-success">Скачать договор</button>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                                <div class="form-group col-sm-4 ">
                                    <label  class="col-form-label" for="exampleInputFile">Загрузить документы</label>
                                    <div class="input-group ">
                                        <div class="custom-file">
                                            <input type="file" name="images[]" class="form-control" id="exampleInputFile"  multiple>
                                        </div>
                                    </div>
                                    @error('images')<p class="text-danger"> {{$message}}</p>@enderror

                                </div>


                                <div class="col-sm-8 ml-4 d-flex flex-wrap" id="imageContainer">

                                    @if($images)
                                        @foreach($images as $image)
                                            <div class="image_block">

                                                <button type="submit" name="delete" value="{{$image->id}}" class="btn btn-sm btn-danger image_btn">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                @if($image->type == 'jpeg' || $image->type == 'jpg' || $image->type == 'png')
                                                <img id="division_image" src="{{url('storage/'.$image->url)}}" alt="division image" class=" mt-3" onclick="change(this.src)" style="width: 150px">
                                                    @else  <button type="submit" name="downloadFile" value="{{$image->id}}" class="btn btn-sm btn-danger download_btn">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-cloud-arrow-down" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M7.646 10.854a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 9.293V5.5a.5.5 0 0 0-1 0v3.793L6.354 8.146a.5.5 0 1 0-.708.708l2 2z"/>
                                                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                                    </svg>
                                                </button>

                                                    <img src="{{url('storage/docs.png')}}" alt="division image" class=" mt-3" style="width: 150px">
                                                @endif

                                            </div>

                                        @endforeach
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="form-group text-center">
{{--                            <a href="{{route('company.index')}}" class="btn btn-success mr-4" style="width: 200px" >Отправить на проверку </a>--}}
                            <input type="submit" class="btn btn-primary mr-4" name="update" value="Сохранить" style="width: 200px">
                            <a href="{{route('company.index')}}" class="btn btn-danger" style="width: 200px">Закрыть </a>
                        </div>
                    </form>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
@endsection

        @section('after_styles')
            <style>
                .image_block {
                    position: relative;
                    width: 150px;
                    margin-right: 20px;
                }
                .image_btn {
                    position: absolute;
                    top: 10px;
                    left: 5px;
                    content: '';
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 25px;
                    height: 25px;
                    padding: 5px;
                    border-radius: 50%;
                    background-color: #c78c91;
                    color: #f6f2f2;
                    cursor: pointer;

                }
                .download_btn {
                    position: absolute;
                    top: 10px;
                    right: 5px;
                    content: '';
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 30px;
                    height: 30px;
                    padding: 5px;
                    border-radius: 50%;
                    background-color: #acdfe3;
                    color: #f6f2f2;
                    cursor: pointer;
                    border: none;
                }
                .image_btn:hover {
                    background-color: #82565a;
                }
                .main_view {
                    display: none;
                    position: absolute;
                    top: 10%;
                    left:10%;
                    width: 80%;
                    height: 80%;
                    z-index: 10;
                }

                .main_view img {

                    width: 100%;
                    height: 100%;
                    object-fit: contain;

                }

                .side_view {
                    display: flex;
                    justify-content: center;
                    flex-wrap: wrap;
                }

                .side_view img {
                    width: 9rem;
                    height: 7rem;
                    object-fit: cover;
                    cursor: pointer;
                    margin: 0.5rem;
                }
            </style>

        @endsection


        @push('script')
            <script>

                const selectImage =  document.getElementById('exampleInputFile');
                const imageContainer = document.getElementById('imageContainer');
                const imageBtns = document.querySelectorAll('.image_btn')



                let imagesArray = []

                function ClickBtnHandler(evt) {
                    const formImage = evt.currentTarget.parentNode
                    const parentImage = formImage.parentNode
                    parentImage.remove()

                }


                selectImage.onchange = evt => {
                    const imgs =document.querySelectorAll('.added_image')
                    if( imgs ) {

                        imgs.forEach(img =>{
                            img.remove()
                        })

                    }

                    const files = selectImage.files

                    for (let i = 0; i < files.length; i++) {
                        console.log(files[i])
                        if(files[i].type === 'image/jpeg') {
                            imagesArray.push(files[i])
                            if (files[i]) {
                                const imageBlock = document.createElement("div");
                                imageBlock.classList.add('image_block');

                                const image = document.createElement("img");
                                image.src = URL.createObjectURL(files[i]);
                                image.alt = "Division image";
                                image.classList.add('added_image');
                                image.style.width = "200px";
                                image.style.marginBottom = "20px";
                                image.style.marginRight = "20px";
                                imageContainer.append(imageBlock);
                                imageBlock.append(image);

                            }

                        }
                    }

                }
                const change = src => {
                    document.querySelector('.main_view').style.display = 'block';
                    const image = document.getElementById('main')
                    image.src = src
                }
                // imageBtn.addEventListener('click', ClickBtnHandler)
            </script>
    @endpush
