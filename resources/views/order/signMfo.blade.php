@extends('layouts.order')

@section('content')
    <div style="padding-top:50px; padding-bottom: 80px">
        @include('components.flash_message')
        <form action="{{route('order.signMfoSend')}}"
              {{--              onsubmit="return login();" --}}
              id="form" method="post" class="w-100" enctype="multipart/form-data">
            @csrf
            <input name="application_id" value="{{$order->application_id}}" type="hidden">


            <div class="container">
                <div style="display: flex; justify-content: space-between; padding-right: 50px; padding-left: 50px">
                    <div>
                        <a href="{{route('statistic.mfo')}}" class="btn btn-secondary" >
                            <i class="fas fa-backward mr-2"></i> Назад
                        </a>

                    </div>

                </div>
                <h2 class="form-title">Отправить код для подписания заявки</h2>
                        <h3 class="my-4">{{$response}}</h3>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <p class="field_label">Код из смс *</p>
                    <input class="form-control" name="sms_code" type="text" placeholder="Введите код из смс" value="{{old('sms_code')}}">
                    @error('sms_code')<p class="text-danger"> {{$message}}</p>@enderror
                </div>

                <div class="form-row">
                    <button id="submit" name="sendData" type="submit" class="btn_submit">Отправить</button>
                </div>
            </div>
        </form>
    </div>


@endsection
