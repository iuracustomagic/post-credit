@extends('layouts.order')
@php
$sent = false;
@endphp
@section('content')
    <div style="padding-top:50px; padding-bottom: 80px">
        @include('components.flash_message')
        <form action="{{route('order.storeMfo')}}"
{{--              onsubmit="return login();" --}}
              id="form" method="post" class="w-100" enctype="multipart/form-data">
            @csrf
            <input name="salesman_id" value="{{$user->id}}" type="hidden">
            <input name="status" value="new" type="hidden">
{{--            <input name="rate" value="{{$rate_value}}" type="hidden">--}}
            <input name="items" id='items' type="hidden">
{{--            <input name="email" id='email' type="hidden">--}}
            <input name="company_id" value="{{$company->id}}" type="hidden">
            <input name="division_id" value="{{$division->id}}" type="hidden">
            <input name="date_sent" value="{{old('dateSent')}}" type="hidden">
            <input name="term_credit" id="term_credit" value="12" type="hidden">
            <input name="email" id='email' type="hidden">

            <div class="container">
            <div style="display: flex; justify-content: space-between; padding-right: 50px; padding-left: 50px">
                <div>
                    <a href="{{route('statistic')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <a href="{{route('logout')}}"> <svg xmlns="http://www.w3.org/2000/svg" height="1.2em" viewBox="0 0 512 512">
                        <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                        <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"/></svg>
                </a>
            </div>
            <h2 class="form-title">Отправить заявку в МФО</h2>
            <div class="form-wrap">

                <div class="form_block mb-4">
                    <div class="row mb-4 align-items-center">
                        <div class="col-lg-6">
                            <p class="mb-2">Номер телефона</p>
                            <input class="form-control" name="phoneSms" placeholder="79000000000" id="user_phone" type="text">
                        </div>
                        <div class="col-lg-2 mt-3">
                            <button class="btn btn-primary h6" name="sendSms" type="submit" id="send_sms_btn" onclick="sendSms()">
                                Получить СМС
                            </button>
                        </div>
                    </div>
                </div>

                <div id="hiddenBlock" style="display: none">
{{--                <div id="hiddenBlock" style="">--}}


                <div class="form_block mb-4">
                    <div class="form_head">
                        <h3 class="form_title">Персональные данные</h3>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Фамилия *</p>
                            <input class="form-control" name="last_name" id="last" type="text" placeholder="Введите фамилию" value="{{isset($copiedData['last_name'])? $copiedData['last_name']: old('last_name')}}">
                            @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Имя *</p>
                            <input class="form-control" name="first_name" id="first" type="text" placeholder="Введите имя"
                                   value="{{isset($copiedData['first_name'])?$copiedData['first_name']:old('first_name')}}">
                            @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Отчество *</p>
                            <input class="form-control" name="surname" id="last" type="text" placeholder="Введите отчество"
                                   value="{{isset($copiedData['surname'])? $copiedData['surname']:old('surname')}}">
                            @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Дата Рождения *</p>
                            <input name="birthday" class="form-control datepicker" id="birthday" type="text" data-inputmask-alias="dd.mm.yyyy"
                                   value="{{isset($copiedData['birthday'])? $copiedData['birthday']:old('birthday')}}"
                                   data-provide="datepicker" id="bdate" placeholder="дд-мм-гггг">
                            @error('birthday')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Номер телефона *</p>
                            <input class="form-control" name="phone" type="tel" id="tell" placeholder="+7 (900) 000-00-00" data-tel-input
                                   value="{{isset($copiedData['phone'])? $copiedData['phone']:old('phone')}}">
                            @error('phone')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Код из смс *</p>
                            <input class="form-control" name="sms_code" type="text" placeholder="Введите код из смс" value="{{old('sms_code')}}">
                            @error('sms_code')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>

                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Дополнительный номер телефона *</p>
                            <input class="form-control" name="additional_phone" type="tel" id="additional_tell" placeholder="+7 (900) 000-00-00" data-tel-input
                                   value="{{old('additional_phone')}}">
                            @error('additional_phone')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>


                    </div>
                </div>

                <div class="form_block mb-4">
                    <div class="form_head">
                        <h3 class="form_title">Документы</h3>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Серия и номер паспорта(без пробела) *</p>
                            <input class="form-control" name="password_id" id="" type="text" placeholder="Введите серию и номер паспорта" value="{{old('password_id')}}">
                            @error('password_id')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Код подразделения *</p>
                            <input class="form-control" name="password_code" id="" type="text" placeholder="Введите код подразделения" value="{{old('password_code')}}">
                            @error('password_code')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Дата выдачи *</p>
                            <input name="password_date" class="form-control datepickerEl" type="text" data-inputmask-alias="dd.mm.yyyy"  data-provide="datepickerEl" id="eldate" placeholder="дд-мм-гггг">
                            @error('password_date')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Кем выдан *</p>
                            <input class="form-control" name="password_by" id="" type="text" placeholder="Введите кем выдан паспорт" value="{{old('password_by')}}">
                            @error('password_by')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Гражданство *</p>
                            <select class="custom-select form-control" name="residence" id="" >
                                    <option value="4">Россия</option>
                                    <option value="1">Таджикистан</option>
                                    <option value="2">Узбекистан</option>
                                    <option value="3">Кыргызстан</option>
                                    <option value="5">Беларусь</option>
                                    <option value="6">Украина</option>
                                    <option value="7">Казахстан</option>
                                    <option value="8">Армения</option>
                                    <option value="9">Азербайджан</option>
                                    <option value="10">Молдавия</option>
                                    <option value="11">Китай</option>

                            </select>
                            @error('residence')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Тип документа *</p>
                            <select class="custom-select form-control" name="doc_set" id="" >

                                <option value="1">Загран/Нац паспорт+временная регистрация+СНИЛС/водительское+Селфи с паспортом</option>
{{--                                <option value="2">Загран/нац+РВП+пост.Регистрация+СНИЛС/водительское+селфи</option>--}}
{{--                                <option value="3">Загран/нац+РВП+временная регистрация+СНИЛС/водительское+селфи</option>--}}
{{--                                <option value="4">Загран/нац+ВНЖ+пост регистрация+СНИЛС/водительское+селфи</option>--}}
{{--                                <option value="5">Загран/нац+ВНЖ+временная регистрация+СНИЛС/водительское+селфи</option>--}}
{{--                                <option value="6">Загран/Нац паспорт+временная регистрация+патент+селфи</option>--}}
{{--                                <option value="7">ID карты/Нац/загран паспорт+ВНЖ+временная регистрация+СНИЛС/водительское+селфи</option>--}}
{{--                                <option value="8">ID карты/Нац/загран паспорт+ВНЖ+постоянная регистрация+СНИЛС/водительское+селфи</option>--}}


                            </select>
                            @error('residence')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="form_block mb-4">
                    <div class="form_head">
                        <h3 class="form_title">Адрес регистрации</h3>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Индекс</p>
                            <input class="form-control" name="address_index" id="" type="text" placeholder="Введите индекс" value="{{old('address_index')}}">
                            @error('address_index')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Регион</p>
                            <input class="form-control" name="address_region" id="" type="text" placeholder="Введите регион" value="{{old('address_region')}}">
                            @error('address_region')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <p class="field_label">Город</p>
                            <input class="form-control" name="address_city" id="" type="text" placeholder="Введите город" value="{{old('address_city')}}">
                            @error('address_city')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-4">
                            <p class="field_label">Улица</p>
                            <input class="form-control" name="address_street" id="" type="text" placeholder="Введите улицу" value="{{old('address_street')}}">
                            @error('address_street')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-4">
                            <p class="field_label">Дом</p>
                            <input class="form-control" name="address_house" id="" type="number" placeholder="Номер дома" value="{{old('address_house')}}">
                            @error('address_house')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-4">
                            <p class="field_label">Корпус</p>
                            <input class="form-control" name="address_block" id="" type="number" placeholder="Номер корпуса" value="{{old('address_block')}}">
                            @error('address_block')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-4">
                            <p class="field_label">Квартира</p>
                            <input class="form-control" name="address_flat" id="" type="number" placeholder="Номер квартиры" value="{{old('address_flat')}}">
                            @error('address_flat')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-4">
                            <p class="field_label">Место рождения *</p>
                            <input class="form-control" name="birth_place" id="" type="text" placeholder="Место рождения" value="{{old('birth_place')}}">
                            @error('birth_place')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                        <div class="col-12 col-md-6 col-lg-3 mb-4">
                            <p class="field_label">Доход *</p>
                            <input class="form-control" name="income_amount" id="" type="number" placeholder="Введите доход" value="{{old('income_amount')}}">
                            @error('income_amount')<p class="text-danger"> {{$message}}</p>@enderror
                        </div>
                    </div>
                </div>


                <div class="form_block mb-4">
                    <div class="form_head">
                        <h3 class="form_title">Товары</h3>
                    </div>
                    <div class="row" id="rowProd">
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <input type="text" id="product" oninput="getProductName(this)"  placeholder="Товар" class="form-control product-name" >
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                            <input type="number" oninput = "getProductPrice(this)"  class="form-control product-price" placeholder="Цена" min="10" max="500000" >
                        </div>
                        <div class="col-12 col-md-6 col-lg-2 mb-4">
                            <input type="number" oninput = "getProductQuantity(this)" class="form-control product-quantity" value="1" placeholder="Количество" min="1" max="50" >
                        </div>
                        <div class="col-12 col-md-6 col-lg-2 mb-4">
                            <a class="btn btn-primary" id="add_btn" onclick="checkAddInputs()">Добавить товар</a>
                        </div>
                    </div>
                </div>

                <div class="form_block mb-5">
                    <div class="row align-items-center ps-4">
                        @if($division['find_mfo'] == 'on' && $division['hide_find_mfo'] != 'on')

                            <div class="col-12 col-md-6 col-lg-3">
                                <label class="form-check-label" for="find_credit">
                                    Подбор кредита
                                </label>
                                <input type="checkbox" class="form-check-input " name="find_credit" checked id="find_credit">
                            </div>
                        @endif

                            <div class=" col-12 col-md-6 col-lg-3" id="sum_container">
                                <label class="checkbox-wrap" for="">
                                    <span class="checkbox-title">Первоначальныц взнос</span>
                                    <input name="initial_fee" class="form-control calc-input" id="fee-input" type="number" value="0"  >
                                    @error('initial_fee')<p class="text-danger"> {{$message}}</p>@enderror
                                </label>

                            </div>

                            <div class=" col-12 col-md-6 col-lg-3" id="">
                                <label class="checkbox-wrap" for="">
                                    <span class="checkbox-title">Срок кредита</span>
                                    <select class=" form-control" id="rate" name="rate" >
                                        @foreach($rates as $item)
                                            <option value="{{$item->value}}" >{{$item->term}} месяцев</option>
                                        @endforeach
                                    </select>

                                </label>

                            </div>
                            <div class=" col-12 col-md-6 col-lg-3" id="sum_container">
                                <label class="checkbox-wrap" for="">
                                    <span class="checkbox-title">Сумма кредита</span>
                                    <input name="sum_credit" class="form-control calc-input" id="credit-input" type="number" readonly >
                                    @error('sum_credit')<p class="text-danger"> {{$message}}</p>@enderror
                                </label>

                            </div>

                            <div class=" col-12 col-md-6 col-lg-3">
                                <label class="checkbox-wrap" for="">
                                    <span class="checkbox-title">Ежемесячный платёж</span>
                                    <input name="month_pay" class="form-control calc-input" id="results-input" type="text" readonly>
                                    @error('month_pay')<p class="text-danger"> {{$message}}</p>@enderror
                                </label>
                            </div>
                    </div>
                </div>

                </div>
                <div class="form-row">
                    <button id="submit" name="sendData" type="submit" class="btn_submit">Отправить</button>
                </div>


            </div>
            </div>
        </form>
    </div>


@endsection

@push('script')
    <script>
        const prodRow = document.querySelector('.g-form__inputs');
        let lastInput = document.querySelector('#last');
        let firstInput = document.querySelector('#first');
        let middleInput = document.querySelector('#middle');
        let tellInput = document.querySelector('#tell');
        let bDate = document.querySelector('#bdate');
        let productInput = document.querySelector('#product');
        const productName = document.querySelectorAll('.product-name');
        const productPrice = document.querySelectorAll('.product-price');
        const productQuantity = document.querySelectorAll('.product-quantity');
        const creditInput = document.getElementById('credit-input');
        // const termInput = document.querySelector('#term-input');
        // const termRange = document.querySelector('#term-range');
        const inputs = document.querySelectorAll('input[type="number"]');
        const email = '@gmail.com';
        let btn = document.querySelector('#submit');
        let form = document.querySelector('#form');
        let addBtn = document.querySelector('#add_btn');
        const results = document.getElementById('results-input').readOnly = true;

        var z = 0;
        const itemsInput = document.getElementById('items')
        const emailSent = document.getElementById('email')
        const initialFee = document.getElementById('fee-input')
        const quantity = document.querySelector('.product-quantity');
        const creditType = document.querySelector('.credit-type');
        const planTerm = document.querySelector('.plan-term-block');
        const findCredit = document.getElementById('find_credit');
        const birthday = document.getElementById('birthday');
        let findCreditValue = 0;
        let items = [{
            name: '',
            price: 0,
            quantity: 1
        }]
        let sms = 0;
        const hideFineCredit = '{{$division->hide_find_mfo}}';

        let rate = document.getElementById('rate')
        let rateText =rate.options[rate.selectedIndex].text
        let tempCredit = rateText.replace(' месяцев', '');

        if(findCredit) {
            if(findCredit.value === 'on') {
                findCreditValue = {{isset($division->find_mfo_value) ? $division->find_mfo_value :0}};
            } else findCreditValue = 0;

            // addFindCredit(findCreditValue)
            findCredit.addEventListener('change', (element)=>{

                if(element.target.checked) {
                    findCreditValue = {{isset($division->find_mfo_value) ? $division->find_mfo_value :0}};
                    // if(!document.getElementById('added_find_credit')) {
                    //     addFindCredit(findCreditValue)
                    // }
                } else {
                    findCreditValue = 0;
                    // document.getElementById('added_find_credit').remove()
                }

                totalPrice()
                mouthly()

            })
        } else if(hideFineCredit ==='on') {
            findCreditValue= {{isset($division->find_mfo_value) ? $division->find_mfo_value :0 }};
            // addFindCredit(findCreditValue)
            totalPrice()
            mouthly()
        }

        initialFee.addEventListener('input', function () {
            creditInput.value -= this.value
        })


        birthday.addEventListener('change', (e)=> {
            console.log(e)
        })
        document.getElementById('rate').addEventListener('change', (e)=>{
            totalPrice()
            mouthly()
            // console.log(e.target.options[rate.selectedIndex].text.replace(' месяцев', ''))
            document.getElementById('term_credit').value = e.target.options[rate.selectedIndex].text.replace(' месяцев', '')
        })
        // console.log(findCreditValue)
        const productNames = document.querySelectorAll('.product-name')
        const smsValue = {{$sms_value}};

        if(smsValue > 0) {
            sms =  smsValue*tempCredit;
        } else sms =0;

        $(document).ready(function () {

            const inputmask_options =
                {
                    mask: "99.99.9999",
                    alias: "date",
                    insertMode: false
                }
            function convertmili( mSeconds )
            {
                return mSeconds / 31536000000;
            }

            $('.datepicker').change(function(e){
                // const dateAsObject = $(this).datepicker( 'getDate').getFullYear();
                const dateAsObject = $(this).datepicker( 'getDate');
                // console.log('choosen', dateAsObject)
                // const today = new Date().getFullYear();
                const today = new Date();
                // const diff = Math.abs(today - dateAsObject);
                // console.log('choosen', diff)
                // const years = Math.floor(diff / (1000 * 60 * 60 * 24 * 30 * 12))
              // const birth_date = dateAsObject.setFullYear(dateAsObject.getFullYear() + 18);
                const years = 60 * 60 * 24 * 30 * 12 * 18;
                // console.log('today', today)
                const diff = convertmili(today - dateAsObject)
                // console.log('diff', today - dateAsObject)
                // console.log('today - choosen', (today - dateAsObject).getFullYear())


            });

            //1706652000000
            //1706715375338
            //63375338
            //559872000
            //65321442
            //565633905872 -18 years
            $(".datepicker").datepicker({
                onSelect: function(dateText, inst) {
                    if(dateText !== inst.lastVal){
                        $(this).change();
                    }

                    const dateAsString = dateText;
                    const dateAsObject = $(this).datepicker( 'getDate' ).getFullYear();
                    const today = new Date().getFullYear();

                    // console.log(dateAsObject)
                    // console.log(today)
                    const diff = Math.abs(dateAsObject - today);
                    // console.log(diff)
                   const years = Math.floor(diff / (1000 * 60 * 60 * 24 * 30 * 12))
                    // console.log(years)

                }
            }).inputmask("99.99.9999", inputmask_options);
            $(".datepickerEl").datepicker().inputmask("99.99.9999", inputmask_options);

            // $(".datepicker").on('input', (e)=>{
            //     console.log(e)
            // })



        });

let timer = 60;
       function sendSms() {
           const phoneValue = document.getElementById('user_phone').value
           let phoneValueReplaced = phoneValue.replace(/[+()-]/g, '')
           phoneValueReplaced = phoneValueReplaced.replace(/\s/g, '')

           // const data = {mobilePhoneNumber: phoneValueReplaced}
           const sendSmsBtn = document.getElementById('send_sms_btn');
           sendSmsBtn.innerHTML = '<span id="" style="font-size: 15px" class="" >Отправить через </span> <span style="font-size: 15px" id="counter">60</span>'
           // console.log(phoneValueReplaced)
           sendSmsBtn.disabled = true;
           let timerInterval = setInterval(function(){
               timer -= 1;
               document.getElementById("counter").innerText = timer;
               if(timer === 0) {
                   clearInterval(timerInterval);
                   sendSmsBtn.disabled = false;
                   sendSmsBtn.innerHTML = 'Получить СМС'
               }
           }, 1000);


        }

        @if(old('triesLeft') >0)
            const last = {{old('triesLeft')}};
            showDiv(`Код подтверждения отправлен, осталось ${last}`)
        sendSms()
        document.getElementById('hiddenBlock').style.display='block';
        @elseif(old('triesLeft') ===0)
        showDiv(`Не осталось более попыток на отправку смс`)
        @endif

        function getProductName(input, j=0) {
            items[j]['name'] = input.value

            itemsInput.value = JSON.stringify(items)
        }
        function getProductPrice(input, j=0) {
            let initialValue = 0
            items[j]['price'] = input.value
            items.forEach(item => {
                initialValue+= Number(item['price'])
            })
            creditInput.value = initialValue +  sms;
            // if(initialFee.value >0) {
            //     creditInput.value-=Number(initialFee.value)
            // }

            itemsInput.value = JSON.stringify(items)
        }
        function getProductQuantity(input, j=0) {
            items[j]['quantity'] = input.value
            let sum = 0
            items.forEach(item => {
                sum += item['quantity'] * item['price']
            })
            if(initialFee.value >0) {
                sum-=Number(initialFee.value)
            }
            creditInput.value = sum + sms


            itemsInput.value = JSON.stringify(items)
        }
        productName.forEach(function (element) {

            if (element.value === '') {
                addBtn.disabled  = true
            } else addBtn.disabled  =false
        })

        function showDiv(text) {
            let div = document.createElement("div");
            div.innerHTML = `<div id="newAlert" class="alert alert-info" role="alert" style=" position: absolute; top:10%; right: 10%">${text}</div>`
            document.body.appendChild(div);
            $("#newAlert").delay(3000).fadeOut("slow", function() {
                document.body.removeChild(div);
            });
        }
        // Добавление новых товаров
        function addInput() {

            items.push({
                name: '',
                price: 0,
                quantity: 1
            })
            const profile = document.getElementById('rowProd');
            const div = document.createElement('div');
            div.classList = 'row ' + 'form-row--' + ++z;
            div.innerHTML = `<div class="col-12 col-md-6 col-lg-4 mb-4"><input type="text" id="product" oninput = "getProductName(this, z)" placeholder="Товар" class="form-control product-name">
            </div><div class="col-12 col-md-6 col-lg-4 mb-4"><input  type="number" id='price_${z}' oninput = "getProductPrice(this, z)" class="form-control product-price" placeholder="Цена" min="10" max="500000" >
            </div><div class="col-12 col-md-6 col-lg-2 mb-4"><input type="number" id='quantity_${z}' oninput = "getProductQuantity(this, z)" class="form-control product-quantity" value="1" placeholder="Количество" min="1" max="50" >
            </div><div class="col-12 col-md-6 col-lg-2"><a class="btn btn-warning" onclick="delInput()">Удалить товар</a></div>`;
            profile.appendChild(div);
            itemsInput.value = z;


        }

        function delInput() {
            var div = document.querySelector('.form-row--' + z);
            const productPrice = document.querySelector('.form-row--' + z + ' .product-price')
            const productQuantity = document.querySelector('.form-row--' + z + ' .product-quantity')

            creditInput.value = creditInput.value - productPrice.value * productQuantity.value;
            // console.log(productPrice.value)
            div.remove();
            items.splice(z, 1);
            const index = items.indexOf(z);
            if (index > -1) {
                items.splice(index, 1);
            }
            --z;
        }



        function totalPrice () {

            let sum = 0
            items.forEach(item => {
                sum += item['quantity'] * item['price']
            })
            if(initialFee.value >0) {
                sum-=Number(initialFee.value)
            }
            let rate = document.getElementById('rate')
            let rateText =rate.options[rate.selectedIndex].text
            let tempCredit = rateText.replace(' месяцев', '');

            if(smsValue > 0) {
                sms =  smsValue*tempCredit;
            } else sms =0;

            if(findCreditValue>0) {
                sum+= + Number(findCreditValue)
            }
            // console.log('sms in totalPrice', sms)
            // console.log('findCreditValue in totalPrice', findCreditValue)
            creditInput.value = sum + sms





        }

        function emailStick () {
            tellFix = tellInput.value.replace(/\D/g,'');
            emailInput = 'Kt' + tellFix + email;
            emailSent.value = emailInput
            return emailInput;
        }

        function mouthly() {

            let rate = document.getElementById('rate')
            let rateText =rate.options[rate.selectedIndex].text
            let tempCredit = rateText.replace(' месяцев', '');
            // console.log(rate.value)
            var monthlyInterest = rate.value * tempCredit / 100 ;
            // console.log(monthlyInterest)
            var x = Math.pow(1 + monthlyInterest,  Number(tempCredit));
            // console.log(parseInt(creditInput.value))
            // var monthlyPayment = (parseInt(creditInput.value) * x * monthlyInterest) / (x - 1);
            // var monthlyPayment = (parseInt(creditInput.value) )  /tempCredit + parseInt(creditInput.value) *monthlyInterest;
            var monthlyPayment = (parseInt(creditInput.value) + (parseInt(creditInput.value) *monthlyInterest)) / tempCredit;
            // console.log('monthlyPayment', monthlyPayment)
            monthlyPayment =Math.round(monthlyPayment)

            let results = document.getElementById('results-input');
            results.value = monthlyPayment.toLocaleString() + ' Рублей';
        }

        totalPrice();
        emailStick();
        mouthly()



        for (const input of inputs) {
            input.addEventListener('input', function () {

                totalPrice();
                emailStick();
                mouthly()

            })
        }

        // prodRow.addEventListener('keyup', function() {
        //     totalPrice();
        //     emailStick();
        //     mouthly()
        // })


        function checkAddInputs() {
            let check = true;
            document.querySelectorAll('.product-name').forEach(function (element) {
                if(element.value === '') {
                    // btn.setAttribute('disabled', 'disabled');
                    showDiv('Введите все поля')
                    check=false;
                    return false;

                }
            })
            document.querySelectorAll('.product-price').forEach(function (element) {

                if(element.value === '') {
                    showDiv('Введите все поля')
                    check=false;
                    return false;
                    // alert('Введите все поля')

                }
            })
            if(check) {
                addInput()
            }

        }

        function checkInputItems() {
            let check = true;
            document.querySelectorAll('.product-name').forEach(function (element) {
                if(element.value === '') {
                    // btn.setAttribute('disabled', 'disabled');
                    showDiv('Введите все поля')
                    check=false;
                    return false;

                }
            })
            document.querySelectorAll('.product-price').forEach(function (element) {
                // console.log('productPrice', element.value)
                if(element.value === '' || element.value == 0) {
                    showDiv('Введите все поля')
                    check=false;
                    return false;

                }
            })
            return check;

        }


        function login() {
            if(checkInputItems()) {
                return true
            } else {
                // showDiv()
                alert('Введите все поля');
                return false;
            }
        }





    </script>
@endpush
