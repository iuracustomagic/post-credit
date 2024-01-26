@extends('layouts.order')

@section('content')
    <div style="padding-top:50px; padding-bottom: 80px">
        @include('components.flash_message')
        <form action="{{route('order.sendSmsCode')}}"
              {{--              onsubmit="return login();" --}}
              id="form" method="post" class="w-100" enctype="multipart/form-data">
            @csrf
            <input name="link" value="{{$link}}" type="hidden">
            <input name="userPhone" value="{{$userPhone}}" type="hidden">


            <div class="container">
                <div style="display: flex; justify-content: space-between; padding-right: 50px; padding-left: 50px">
                    <div>
                        <a href="{{route('order.index')}}" class="btn btn-secondary" >
                            <i class="fas fa-backward mr-2"></i> Назад
                        </a>

                    </div>

                </div>
                <h2 class="form-title mb-4">Отправить код из смс</h2>

                <div class="form_block mb-4">
                    <div class="row mb-4 align-items-center">
                        <div class="col-lg-6">
                            <p class="field_label">Код из смс *</p>
                            <input class="form-control" name="sms_code" type="text" placeholder="Введите код из смс" value="{{old('sms_code')}}">
                        </div>
                        <div class="col-lg-2 mt-3">
                            <button class="btn btn-primary h6" name="sendSms" type="submit" id="send_sms_btn" >
                                Получить СМС
                            </button>
                        </div>
                    </div>
                </div>


                <div class="form-row">
                    <button id="submit" name="sendData" type="submit" class="btn_submit">Отправить</button>
                </div>
            </div>
        </form>
    </div>


@endsection

@push('script')
    <script>
        function showDiv(text) {
            let div = document.createElement("div");
            div.innerHTML = `<div id="newAlert" class="alert alert-info" role="alert" style=" position: absolute; top:10%; right: 10%">${text}</div>`
            document.body.appendChild(div);
            $("#newAlert").delay(3000).fadeOut("slow", function() {
            document.body.removeChild(div);
            });
        }
        let timer = 60;

        function sendSms() {

            const sendSmsBtn = document.getElementById('send_sms_btn');
            sendSmsBtn.innerHTML = '<span id="" style="font-size: 15px" class="" >Отправить через </span> <span style="font-size: 15px" id="counter">60</span>'
            // console.log(phoneValueReplaced)
            sendSmsBtn.disabled = true;
            let timerInterval = setInterval(function () {
                timer -= 1;
                document.getElementById("counter").innerText = timer;
                if (timer === 0) {
                    clearInterval(timerInterval);
                    sendSmsBtn.disabled = false;
                    sendSmsBtn.innerHTML = 'Получить СМС'
                }
            }, 1000);
        }

        @if(isset($code))
        showDiv(`Код подтверждения отправлен`)
        sendSms()
        @else
        showDiv(`Код подтверждения не совпадает`)
        @endif
    </script>
@endpush
