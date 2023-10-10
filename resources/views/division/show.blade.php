@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mt-3">
                    <a href="{{route('division.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4 ">


                    <div class="row w-75  mt-3 fs-5">

                        <div class="row  mb-3">
                            <div class="form-group col-sm-4">
                                <span >Наименование:</span>
                                <span class="fw-bold ml-3">{{$division->name}}</span>
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Адресс ТТ:</span>
                                <span class="fw-bold ml-3">{{$division->address}}</span>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-sm-4">
                                <span>Shop ID:</span>
                                <span class="fw-bold ml-3">{{$division->shop_id}}</span>
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Show case ID:</span>
                                <span class="fw-bold ml-3">{{$division->show_case_id}}</span>
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Цена смс:</span>
                                <span class="fw-bold ml-3">{{$division->price_sms}}</span>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="form-group col-sm-4">
                                <span>Ставка по кредиту:</span>
                                <span class="fw-bold ml-3">{{isset($rate->value) ? $rate->value: 0}}%</span>
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Ставка по рассрочке:</span>
                                <span class="fw-bold ml-3">{{isset($plan->value) ? $plan->value : 0}}%</span>
                                <p class="fw-bold ml-3">{{isset($plan->term) ? $plan->term  : 0}} месяцев</p>
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Тип магазина:</span>
                                <span class="fw-bold ml-3">{{$division->typeTitle}}</span>
                            </div>

                        </div>

                        <div class="row mb-3">
                            <div class="form-group col-sm-4">
                                <span>Статус:</span>
                                <span class="fw-bold ml-3">{{$division->statusTitle}}</span>
                            </div>
                            <div class="form-group col-sm-4">
                                <span>Организация:</span>
                                <span class="fw-bold ml-3">{{$company->name}}</span>
                            </div>

                        </div>

                        <div class="row mb-5">
                            @if($images)
                                @foreach($images as $image)
                                    <img id="division_image" src="{{url('storage/'.$image->url)}}" alt="division image" class="ml-4 mt-3" style="width: 300px">
                                @endforeach
                            @endif


                        </div>


                    </div>



            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

@endsection
