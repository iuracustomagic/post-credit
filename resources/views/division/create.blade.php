@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('division.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <div class="row mb-2 mt-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Создать торговую точку</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->


        @include('components.flash_message');
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4 ">
                <div class="w-75 d-flex justify-content-end">

                </div>
                <div class="row mt-4 ">
                    <form action="{{route('division.store')}}" method="post" class="w-75" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" class="form-control" name="created_by" value="{{\Illuminate\Support\Facades\Auth::id()}}" >

                        <nav class="w-100">
                            <div class="nav nav-tabs" id="product-tab" role="tablist">
                                <a class="nav-item nav-link active fs-5" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">
                                    Общие
                                </a>
                                <a class="nav-item nav-link fs-5" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">
                                    Банк
                                </a>
                                <a class="nav-item nav-link fs-5" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">
                                    МФО
                                </a>
                            </div>
                        </nav>
                        <div class="tab-content p-3 " style="min-height: 500px" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                <div class="row mb-3">
                                    <div class="form-group col-sm-4 mr-4">
                                        <input type="text" class="form-control" name="name" placeholder="Наименование" value="{{old('name')}}">
                                        @error('name')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                    <div class="form-group col-sm-4 mr-4">
                                        <input type="text" class="form-control" name="address" placeholder="Адресс ТТ" value="{{old('address')}}">
                                        @error('address')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>

                                </div>
                                <div class="form-group col-sm-4 d-flex">
                                    <select class="custom-select form-control " name="type" id="exampleSelectBorder" >
                                        <option value="1" selected>РОС - магазин</option>
                                        <option value="2">Интернет магазин</option>
                                    </select>
                                    @error('type')<p class="text-danger"> {{$message}}</p>@enderror
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-sm-4 ">
                                        <label  class="col-form-label" for="exampleInputFile">Фото ТТ</label>
                                        <div class="input-group ">
                                            <div class="custom-file">
                                                <input type="file" name="images[]" class="form-control" id="exampleInputFile" multiple="multiple">
                                            </div>
                                        </div>
                                        @error('images')<p class="text-danger"> {{$message}}</p>@enderror

                                    </div>
                                    <div class="col-sm-4 ml-3" id="imageContainer">

                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="form-group col-sm-4 mr-4">
                                        <label for="statusInput">Статус</label>
                                        <select class="custom-select form-control ml-2" name="status" id="statusInput" >
                                            <option value="1">Активна</option>
                                            <option value="0">Не активна</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-sm-4 mr-4">
                                        <label for="statusInput">Привязать к ООО</label>
                                        <select class="custom-select form-control ml-2" name="company_id" id="statusInput" >
                                            @foreach($companies as $company)
                                                <option value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                <div class="row mb-3">
                                    <div class="form-group col-sm-4 mr-4">
                                        <select class="custom-select form-control " name="shop_id" >
                                            <option selected disabled>Shop ID</option>
                                            <option value="1169f535-3ded-4cce-b515-93cea6a45ed2">ИП Кауфман</option>
                                            <option value="4dcaa2b7-5cf1-4be2-b05b-3406ca7273b6">ООО Купитутор</option>

                                        </select>
                                        @error('shop_id')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                    <div class="form-group col-sm-4 mr-4">
                                        <input type="text" class="form-control" name="show_case_id" placeholder="Show case ID" value="{{old('show_case_id')}}">
                                        @error('show_case_id')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                    <div class="form-group col-sm-4 mr-4">
                                        <input type="text" class="form-control" name="price_sms" placeholder="Цена смс" value="{{old('price_sms')}}">
                                        @error('price_sms')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>


                                </div>
                                <div class="row mb-3">
                                    <div class="form-check col-sm-2 pl-5 mr-4 ">
                                        <input class="form-check-input" type="checkbox" name="find_credit"  id="find_credit">
                                        @error('find_credit')<p class="text-danger"> {{$message}}</p>@enderror
                                        <label class="form-check-label" for="find_credit">
                                            Подбор кредита
                                        </label>
                                    </div>
                                    <div class="form-check col-sm-3 pl-5 mr-4 ">
                                        <input class="form-check-input" type="checkbox" name="hide_find_credit"  id="hide_find_credit">
                                        @error('hide_find_credit')<p class="text-danger"> {{$message}}</p>@enderror
                                        <label class="form-check-label" for="hide_find_credit">
                                            Скрыть подбор кредита
                                        </label>
                                    </div>
                                    <div class="form-group col-sm-3 ">
                                        <input class="form-control " name="find_credit_value" placeholder="Цена" type="text">
                                        @error('find_credit_value')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="form-group col-sm-2 mr-4">
                                        <select class="custom-select form-control" name="rate_id" id="exampleSelectBorder" >
                                            <option selected disabled>Ставка по кредиту</option>
                                            @foreach($rates as $rate)
                                                <option value="{{$rate->id}}">{{$rate->value}}%</option>
                                            @endforeach

                                        </select>
                                        @error('rate_id')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>

                                    <div class="form-group col-sm-2 mr-4">
                                        <select class="custom-select form-control" name="plan_id" >
                                            <option selected disabled>Ставка по рассрочке</option>
                                            @foreach($installments as $installment)
                                                <option value="{{$installment->id}}">{{$installment->value}}%</option>
                                            @endforeach
                                        </select>
                                        @error('plan_id')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                    <div class="form-group col-sm-3 mr-3 d-flex">
                                        <p>Срок по рассрочке</p>
                                        <select class="form-control plans ml-2" name="installments[]" multiple="multiple" >
                                            @foreach($plans as $plan)
                                                <option value="{{$plan->id}}">{{$plan->term}}</option>
                                            @endforeach
                                        </select>
                                        @error('installments')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group d-flex col-sm-4 mr-4">
                                        <p>Ставка по кредиту если продавец выключил подбор кредита</p>
                                        <select class="custom-select form-control" name="rate_if_off" id="rateIfOff" >

                                            @foreach($rates as $rate)
                                                <option value="{{$rate->id}}">{{$rate->value}}%</option>
                                            @endforeach

                                        </select>
                                        @error('rate_if_off')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                                <div class="row mb-3">
                                    <div class="form-check col-sm-2 pl-5 mr-4 ">
                                        <input class="form-check-input" type="checkbox" name="find_mfo"  id="find_mfo">
                                        @error('find_mfo')<p class="text-danger"> {{$message}}</p>@enderror
                                        <label class="form-check-label" for="find_mfo">
                                            Подбор МФО
                                        </label>
                                    </div>
                                    <div class="form-check col-sm-3 pl-5 mr-4 ">
                                        <input class="form-check-input" type="checkbox" name="hide_find_mfo"  id="hide_find_mfo">
                                        @error('hide_find_mfo')<p class="text-danger"> {{$message}}</p>@enderror
                                        <label class="form-check-label" for="hide_find_mfo">
                                            Скрыть подбор МФО
                                        </label>
                                    </div>
                                    <div class="form-group col-sm-3 ">
                                        <input class="form-control " name="find_mfo_value" placeholder="Цена" type="text">
                                        @error('find_mfo_value')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4 mr-4">
                                        <input type="text" class="form-control" name="price_sms_mfo" placeholder="Цена смс" value="{{old('price_sms_mfo')}}">
                                        @error('price_sms_mfo')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                    <div class="form-group d-flex col-sm-4 mr-4">
                                        <p class="mr-4">Сегмент</p>
                                        <select class="custom-select form-control" name="segment_id" id="" >

                                            @foreach($segments as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach

                                        </select>
                                        @error('rate_if_off')<p class="text-danger"> {{$message}}</p>@enderror
                                    </div>
                                </div>

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

                const selectImage =  document.getElementById('exampleInputFile');
                const imageContainer = document.getElementById('imageContainer');
                $('.plans').select2()

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

                        imagesArray.push(files[i])
                        if (files[i]) {
                            const image = document.createElement("img");
                            image.src = URL.createObjectURL(files[i]);
                            image.alt = "Division image";
                            image.classList.add('added_image');
                            image.style.width = "300px";
                            image.style.marginBottom = "20px"
                            imageContainer.append(image);
                        }
                    }

                }


            </script>
    @endpush
