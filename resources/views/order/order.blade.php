@extends('layouts.order')

@section('content')
<div style="padding-top:50px">
    @include('components.flash_message')
    <form action="{{route('order.store')}}" onsubmit="return login();" id="form" method="post" class="w-100" enctype="multipart/form-data">
        @csrf
        <input name="salesman_id" value="{{$user->id}}" type="hidden">
        <input name="status" value="new" type="hidden">
        <input name="rate" value="{{$rate_value}}" type="hidden">
        <input name="items" id='items' type="hidden">
        <input name="email" id='email' type="hidden">
        <input name="company_id" value="{{$company->id}}" type="hidden">
        <input name="division_id" value="{{$division->id}}" type="hidden">

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
<h2 class="form-title">Отправить заявку в банк</h2>
<div class="form-wrap">

    <div class="g-form__inputs">
        <div class="form-row">
            <input name="last_name" type="text" id="last" placeholder="Фамилия" value="{{old('last_name')}}">
            @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
            <input name="first_name" type="text" id="first" placeholder="Имя" value="{{old('first_name')}}">
            @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
            <input name="surname" type="text" id="middle" placeholder="Отчество" value="{{old('surname')}}">
            @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
        </div>
        <div class="form-row">
            <input name="birthday" class="datepicker" type="text" data-inputmask-alias="dd.mm.yyyy"  data-provide="datepicker" id="bdate" placeholder="Дата Рождения">
            @error('birthday')<p class="text-danger"> {{$message}}</p>@enderror
            <input name="phone" type="tel" id="tell" placeholder="Номер телефона" data-tel-input value="{{old('phone')}}">
            @error('phone')<p class="text-danger"> {{$message}}</p>@enderror


        </div>
        <div class="form-row prod-row" id="rowProd">
            <input type="text" id="product" oninput="getProductName(this)"  placeholder="Товар" class="product-name" >
            <input type="number" oninput = "getProductPrice(this)"  class="product-price" placeholder="Цена" min="10" max="500000" >
            <input type="number" oninput = "getProductQuantity(this)" class="product-quantity" value="1" placeholder="Количество" min="1" max="50" >
            <a class="add-btn" id="add_btn" onclick="checkAddInputs()">Добавить товар</a>
        </div>
        <div class=" row w-75 align-items-center " style="padding-left:10%">
            @if($division['find_credit'] == 'on' && $division['hide_find_credit'] != 'on')

            <div class="form-check col-sm-2 ">
                <label class="form-check-label" for="find_credit">
                    Подбор кредита
                </label>
                <input type="checkbox" class="form-check-input " name="find_credit" checked id="find_credit">
            </div>
            @endif
{{--            <div class="col-sm-2">--}}
{{--                <p class="mb-2">Кредит/Рассрочка</p>--}}
{{--                <select class="credit-type" name="credit_type" >--}}
{{--                    <option value="1" selected>Кредит</option>--}}
{{--                    <option value="2">Рассрочка</option>--}}
{{--                </select>--}}
{{--            </div>--}}


                <div class="col-sm-3 btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check credit-type" name="credit_type" onclick="handleTypeClick(this);" id="btnradio1" value="1" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="btnradio1">Кредит</label>

                    <input type="radio" class="btn-check credit-type" name="credit_type" onclick="handleTypeClick(this);" id="btnradio2" value="2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio2">Рассрочка</label>

                </div>

{{--                </div>--}}
            <div class="col-sm-4 row align-items-center plan-term-block" style="display: none">
                <div class="col-sm-6">
                    <p class="">Срок рассрочки</p>
                </div>
              <div class="col-sm-6">
                  <select class=" " name="plan_term" >
                      @foreach($installments as $item)
                          <option value="{{$item->id}}" selected>{{$item->term}}</option>
                      @endforeach
                  </select>
              </div>

            </div>


        </div>

        <div class="form-row row w-75">
            <div class="calc-section col-sm-3">
                <label class="checkbox-wrap" for="">
                    <span class="checkbox-title">Первоначальный взнос</span>
                    <input name="initial_fee" class="calc-input" id="fee-input" type="number" value="0" >

                </label>
            </div>

            <div class="col-sm-3 calc-section">
                <label class="checkbox-wrap" for="">
                    <span class="checkbox-title">Срок кредита</span>
                    <input name="term_credit" class="calc-input" id="term-input" type="number" min="3" max="36" value="3">
                    @error('term_credit')<p class="text-danger"> {{$message}}</p>@enderror
                </label>
                <input class="calc-range" id="term-range" type="range" min="3" max="36" value="3" step="1">
            </div>
            <div class="calc-section col-sm-3">
                <label class="checkbox-wrap" for="">
                    <span class="checkbox-title">Сумма кредита</span>
                    <input name="sum_credit" class="calc-input" id="credit-input" type="number" readonly >
                    @error('sum_credit')<p class="text-danger"> {{$message}}</p>@enderror
                </label>
            </div>
            <div class="calc-section col-sm-3">
                <label class="checkbox-wrap" for="">
                    <span class="checkbox-title">Ежемесячный платёж</span>
                    <input  class="calc-input" id="results-input" type="text" value="">

                </label>
            </div>
        </div>

        <div class="form-row">
            <button id="submit" type="submit" class="btn btn-warning">Купить в кредит</button>
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
        const termInput = document.querySelector('#term-input');
        const termRange = document.querySelector('#term-range');
        const inputs = document.querySelectorAll('input[type="number"]');
        const email = '@gmail.com';
        let btn = document.querySelector('#submit');
        let form = document.querySelector('#form');
        let addBtn = document.querySelector('#add_btn');
        var results = document.getElementById('results-input').readOnly = true;


        var z = 0;
        const itemsInput = document.getElementById('items')
        const emailSent = document.getElementById('email')
        const initialFee = document.getElementById('fee-input')
        const quantity = document.querySelector('.product-quantity');
        const creditType = document.querySelector('.credit-type');
        const planTerm = document.querySelector('.plan-term-block');
        const findCredit = document.getElementById('find_credit');
        let findCreditValue = 0;
        let items = [{
            name: '',
            price: 0,
            quantity: 1
        }]
        let sms = 0;
        const hideFineCredit = '{{$division->hide_find_credit}}';
        let rateValue = String({{$rate_value}});

        if(findCredit) {
            if(findCredit.value === 'on') {
                findCreditValue = {{isset($division->find_credit_value) ? $division->find_credit_value :0  }}
            } else {
                findCreditValue = 0;
                rateValue = String({{$rateIfOffValue}});

            }
            findCredit.addEventListener('change', (element)=>{

                if(element.target.checked) {
                    findCreditValue = {{isset($division->find_credit_value) ? $division->find_credit_value :0 }}
                        rateValue = String({{$rate_value}});
                } else {
                    findCreditValue = 0;
                    rateValue = String({{$rateIfOffValue}});

                }
                totalPrice()
                monthlyPrice()
            })
        } else if(hideFineCredit ==='on') {
            findCreditValue= {{isset($division->find_credit_value) ? $division->find_credit_value :0 }};
            totalPrice()
            monthlyPrice()
        }


        // console.log(findCreditValue)
        const productNames = document.querySelectorAll('.product-name')
        const smsValue = {{$sms_value}};
        $(document).ready(function () {

            const inputmask_options =
                {
                    mask: "99.99.9999",
                    alias: "date",
                    insertMode: false
                }

            $(".datepicker").datepicker().inputmask("99.99.9999", inputmask_options);


        });


        if(smsValue > 0 && smsValue<=159) {
            sms = 159
        } else if (smsValue>159)
        {
            sms = smsValue
        }

        // Проверить заполнение полей
        function test () {
            if (!(lastInput.value == '') && !(firstInput.value == '') && !(middleInput.value == '') && !(tellInput.value == '') && !(productInput.value == '')
                && !(productPrice.value == '') && !(quantity.value == '')) {
                btn.removeAttribute('disabled');
            } else {
                btn.setAttribute('disabled', 'disabled');
            }
        }


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
            creditInput.value = initialValue + (parseInt(termInput.value) * sms);
            if(initialFee.value >0) {
                creditInput.value-=Number(initialFee.value)
            }

            itemsInput.value = JSON.stringify(items)
        }
        function getProductQuantity(input, j=0) {
            items[j]['quantity'] = input.value
            let sum = 0
            items.forEach(item => {
                sum += item['quantity'] * item['price']
            })
            creditInput.value = sum + (parseInt(termInput.value) * sms)
            if(initialFee.value >0) {
                creditInput.value-=Number(initialFee.value)
            }

            itemsInput.value = JSON.stringify(items)
        }
        productName.forEach(function (element) {

            if (element.value === '') {
                addBtn.disabled  = true
            } else addBtn.disabled  =false
        })

        function showDiv() {
            let div = document.createElement("div");
            div.innerHTML = '<div id="newAlert" class="alert alert-info" role="alert" style=" position: absolute; top:10%; right: 10%">Введите все поля</div>'
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
            div.classList = 'form-row prod-row ' + 'form-row--' + ++z;
            div.innerHTML = `<input type="text" id="product" oninput = "getProductName(this, z)" placeholder="Товар" class="product-name">
            <input  type="number" id='price_${z}' oninput = "getProductPrice(this, z)" class="product-price" placeholder="Цена" min="10" max="500000" >
            <input type="number" id='quantity_${z}' oninput = "getProductQuantity(this, z)" class="product-quantity" value="1" placeholder="Количество" min="1" max="50" >
             <a class="del-btn" onclick="delInput()">Удалить товар</a>`;
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



        termRange.addEventListener('input', function () {
            termInput.value = termRange.value;
        })

        termInput.addEventListener('input', function () {
            termRange.value = termInput.value;
        })

        initialFee.addEventListener('input', function () {
            creditInput.value -= this.value
        })

        function smsPrice() {
            return  (parseInt(termInput.value) * sms);

        }

        function totalPrice () {

            let sum = 0
            items.forEach(item => {
                sum += item['quantity'] * item['price']
            })

            if(creditType.value === '1' && creditType.checked ===true) {
                creditInput.value = sum + (parseInt(termInput.value) * sms)
                if(findCreditValue>0) {
                    creditInput.value= Number(creditInput.value) + Number(findCreditValue)
                }
            } else creditInput.value = sum


            if(initialFee.value >0) {
                creditInput.value-=Number(initialFee.value)
            }

        }

        function emailStick () {
            tellFix = tellInput.value.replace(/\D/g,'');
            emailInput = 'Kt' + tellFix + email;
            emailSent.value = emailInput
            return emailInput;
        }

        function monthlyPrice () {

            let tempCredit = 0;
            let interest =0;
            if(creditType.value === '1'){

                // console.log('rateValue', rateValue)
                interest = rateValue.replace(/,/g, '.')
                tempCredit = parseInt(creditInput.value);
            } else {
                const installmentValue = '{{$installment_value}}';
                // console.log(installmentValue)
                interest = installmentValue.replace(/,/g, '.')
                tempCredit = parseInt(creditInput.value)
            }

            var monthlyInterest = interest / 100 / 12;
            var x = Math.pow(1 + monthlyInterest, termInput.value);
            var monthlyPayment = (tempCredit * x * monthlyInterest) / (x - 1);
            monthlyPayment = monthlyPayment | 0;
            let results = document.getElementById('results-input');
            results.value = monthlyPayment.toLocaleString() + ' Рублей';
        }




        totalPrice();
        emailStick();
        monthlyPrice();




        for (const input of inputs) {
            input.addEventListener('input', function () {

                totalPrice();
                emailStick();
                monthlyPrice();

            })
        }

        prodRow.addEventListener('keyup', function() {

            totalPrice();
            emailStick();
            monthlyPrice();

        })



        document.getElementById('term-range').addEventListener('click', function() {
            monthlyPrice();
            totalPrice();


        })
        function checkAddInputs() {
            let check = true;
            document.querySelectorAll('.product-name').forEach(function (element) {
                if(element.value === '') {
                    // btn.setAttribute('disabled', 'disabled');
                    showDiv()
                    check=false;
                    return false;

                }
            })
            document.querySelectorAll('.product-price').forEach(function (element) {

                if(element.value === '') {
                    showDiv()
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
                    showDiv()
                    check=false;
                    return false;

                }
            })
            document.querySelectorAll('.product-price').forEach(function (element) {
                // console.log('productPrice', element.value)
                if(element.value === '' || element.value == 0) {
                    showDiv()
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


        function handleTypeClick(el){

            let sum = 0
            items.forEach(item => {
                sum += item['quantity'] * item['price']
            })

            if(el.value === '2') {
                planTerm.style.display = "flex"
                creditInput.value = sum
            } else {
                planTerm.style.display = "none"
                creditInput.value = sum + (parseInt(termInput.value) * sms)
                if(findCreditValue>0) {
                    creditInput.value= Number(creditInput.value) + Number(findCreditValue)
                }
            }

            monthlyPrice();
        }


    </script>
@endpush
