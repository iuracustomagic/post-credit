@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div>
                        <a href="{{route('manager.index')}}" class="btn btn-secondary" >
                            <i class="fas fa-backward mr-2"></i> Назад
                        </a>

                    </div>
                    <div class="col-sm-6">
                        <h1 class="m-0">Настройки бонусов</h1>
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
                    <form action="{{route('bonus.update')}}" method="post" class="w-100">
                        @csrf
                        @method('patch')
                        <div class="row  mt-3 mb-3">
                            <h4 class="mb-3">За кредиты</h4>
                            <div class="form-group col-sm-3">
                                <div class="d-flex align-items-center">
                                <span>21%</span>
                                <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$credit1->reward}}" name="credit_1" >
                                @error('credit_1')<p class="text-danger"> {{$message}}</p>@enderror
                                    <span>%</span>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <div class="d-flex  align-items-center">
                                <span>25%</span>
                                <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$credit2->reward}}" name="credit_2" >
                                @error('credit_2')<p class="text-danger"> {{$message}}</p>@enderror
                                    <span>%</span>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <div class="d-flex  align-items-center">
                                    <span>29%</span>
                                    <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$credit3->reward}}" name="credit_3" >
                                    @error('credit_3')<p class="text-danger"> {{$message}}</p>@enderror
                                    <span>%</span>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <div class="d-flex  align-items-center">
                                    <span>32.5%</span>
                                    <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$credit4->reward}}" name="credit_4" >
                                    @error('credit_4')<p class="text-danger"> {{$message}}</p>@enderror
                                    <span>%</span>
                                </div>
                            </div>
                        </div>

                        <div class="row  mt-3 mb-3">

                            <div class="form-group col-sm-3">
                                <div class="d-flex align-items-center">
                                    <span>За смс</span>
                                    <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$sms->reward}}" name="sms" >
                                    @error('sms')<p class="text-danger"> {{$message}}</p>@enderror
                                    <span>%</span>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <div class="d-flex  align-items-center">
                                    <span>С рефералов</span>
                                    <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$referral->reward}}" name="referral" >
                                    @error('referral')<p class="text-danger"> {{$message}}</p>@enderror
                                    <span>%</span>
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


            </script>
    @endpush
