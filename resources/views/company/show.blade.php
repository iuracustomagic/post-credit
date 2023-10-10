@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('company.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад</a>
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

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4 ">
                <div class="w-75 d-flex justify-content-end">
                    <div class=" fs-4">
                        <span class="mr-2">Статус:</span>
                        <span class="text-primary">{{$company->statusTitle}}</span>
                    </div>

                </div>
                <div class="row mt-4 ">

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
                        <div class="tab-content p-3 fs-5" style="min-height: 500px" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                <div class="row w-75  mt-3">

                                    <div class="row  ">
                                        <div class="form-group col-sm-4">
                                            <span >Фамилия:</span>
                                            <span class="fw-bold ml-3">{{$user->last_name}}</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <span>Имя:</span>
                                            <span class="fw-bold ml-3">{{$user->first_name}}</span>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <span>Отчество:</span>
                                            <span class="fw-bold ml-3">{{$user->surname}}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="form-group col-sm-4">
                                            <span>Телефон:</span>
                                            <span class="fw-bold ml-3">{{$user->phone}}</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <span>Почта:</span>
                                            <span class="fw-bold ml-3">{{$user->email}}</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <span>Логин:</span>
                                            <span>{{$user->login}}</span>
                                        </div>

                                    </div>

                                    <h3>Паспорт</h3>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <span>Серия\номер:</span>
                                            <span class="fw-bold ml-3">{{$passwordDate->number}}</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <span>Кем выдан:</span>
                                            <span class="fw-bold ml-3">{{$passwordDate->by}}</span>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <span>Дата выдачи:</span>
                                            <span class="fw-bold ml-3">{{$passwordDate->date}}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="form-group col-sm-8">
                                            <span>Прописка:</span>
                                            <span class="fw-bold ml-3">{{$passwordDate->registration}}</span>
                                        </div>

                                    </div>



                                </div>
                            </div>

                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                <div class="row w-75 mt-3">

                                    <div class="row  ">
                                        <div class="form-group col-sm-4">
                                            <span>Наименование организации:</span>
                                            <span class="fw-bold ml-3">{{$company->name}}</span>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <span>ИНН:</span>
                                            <span class="fw-bold ml-3">{{$company->inn}}</span>
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <span>ОГРН:</span>
                                            <span class="fw-bold ml-3">{{$company->ogrn}}</span>
                                        </div>
                                    </div>
                                    <div class="row  ">
                                        <div class="form-group col-sm-12">
                                            <span>Адресс:</span>
                                            <span class="fw-bold ml-3">{{$company->address}}</span>
                                        </div>
                                    </div>
                                    <div class="row  ">
                                        <div class="form-group col-sm-6">
                                            <span>Расчетный счет:</span>
                                            <span class="fw-bold ml-3">{{$company->checking_account}}</span>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <span>Наименование банка:</span>
                                            <span class="fw-bold ml-3">{{$company->bank_name}}</span>
                                        </div>
                                    </div>
                                    <div class="row  ">
                                        <div class="form-group col-sm-6">
                                            <span>Кореспонденский счет:</span>
                                            <span class="fw-bold ml-3">{{$company->correspond_account}}</span>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <span>БИК:</span>
                                            <span class="fw-bold ml-3">{{$company->bik}}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                                <div class="col-sm-8 ml-4 d-flex flex-wrap" id="imageContainer">
                                    @if($images)
                                        @foreach($images as $image)
                                            <div class="image_block">

                                               @if($image->type == 'jpeg' || $image->type == 'jpg' || $image->type == 'png')
                                                    <img id="division_image" src="{{url('storage/'.$image->url)}}" alt="division image" class=" mt-3" style="width: 150px">
                                                @else <img src="{{url('storage/docs.png')}}" alt="division image" class=" mt-3" style="width: 150px">
                                                @endif

                                            </div>

                                        @endforeach
                                    @endif

                                </div>

                        </div>


                </div>




            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

@endsection
