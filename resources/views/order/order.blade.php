<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" href="{{asset('css/normalize.css')}} ">
    <link rel="stylesheet" href="{{asset('css/style.css')}} ">

    <title>Отправление заявки</title>

    <script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>
</head>
<body>
<div style="padding-top:50px">
    @include('components.flash_message')
    <form action="{{route('order.store')}}" id="form" method="post" class="w-100" enctype="multipart/form-data">
        @csrf
        <input name="salesman_id" value="{{$user->id}}" type="hidden">
        <input name="status" value="new" type="hidden">
        <input name="rate" value="{{$rate_value}}" type="hidden">
        <input name="items" id='items' type="hidden">
        <input name="email" id='email' type="hidden">
        <input name="company_id" value="{{$company->id}}" type="hidden">
        <input name="division_id" value="{{$division->id}}" type="hidden">

<div style="display: flex; justify-content: end; padding-right: 50px">
    <a href="{{route('logout')}}"> <svg xmlns="http://www.w3.org/2000/svg" height="1.2em" viewBox="0 0 512 512">
            <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
            <path d="M502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128zM160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0z"/></svg>
    </a>
</div>
<h2 class="form-title">Введите данные</h2>
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
            <input type="number" oninput = "getProductPrice(this)"  class="product-price" placeholder="Цена" min="3000" max="290000" >
            <input type="number" oninput = "getProductQuantity(this)" class="product-quantity" value="1" placeholder="Количество" min="1" max="50" >
            <a class="add-btn" onclick="addInput()">Добавить товар</a>
        </div>
        <div class="form-row row w-75">
            <div class="col-sm-2">
                <p class="mb-2">Кредит/Рассрочка</p>
                <select class=" " name="credit_type" >
                    <option value="1" selected>Кредит</option>
                    <option value="2">Рассрочка</option>
                </select>
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

<!-- jQuery -->

{{--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>--}}
{{--<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>--}}

<!-- jQuery -->
<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('adminlte/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <script src="{{asset('js/phone.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
<script>
    const prodRow = document.querySelector('.g-form__inputs');
    let lastInput = document.querySelector('#last');
    let firstInput = document.querySelector('#first');
    let middleInput = document.querySelector('#middle');
    let tellInput = document.querySelector('#tell');
    let bDate = document.querySelector('#bdate');
    let productInput = document.querySelector('#product');
    const productPrice = document.querySelectorAll('.product-price');
    const productQuantity = document.querySelectorAll('.product-quantity');
    const creditInput = document.getElementById('credit-input');
    const termInput = document.querySelector('#term-input');
    const termRange = document.querySelector('#term-range');
    const inputs = document.querySelectorAll('input');
    const email = '@gmail.com';
    let btn = document.querySelector('#submit');
    let form = document.querySelector('#form');
    var results = document.getElementById('results-input').readOnly = true;
    var interest = 21;
    var z = 0;
    const itemsInput = document.getElementById('items')
    const emailSent = document.getElementById('email')
    const quantity = document.querySelector('.product-quantity');

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

let sms = 0;
if(smsValue<=159) {
    sms = 159
} else sms = smsValue;

    // Проверить заполнение полей
    function test () {
        if (!(lastInput.value == '') && !(firstInput.value == '') && !(middleInput.value == '') && !(tellInput.value == '') && !(productInput.value == '')
            && !(productPrice.value == '') && !(quantity.value == '')) {
            btn.removeAttribute('disabled');
        } else {
            btn.setAttribute('disabled', 'disabled');
        }
    }
    let items = [{
        name: '',
        price: 0,
        quantity: 1
    }]

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

        itemsInput.value = JSON.stringify(items)
    }
    function getProductQuantity(input, j=0) {
        items[j]['quantity'] = input.value
        let sum = 0
        items.forEach(item => {
          sum += item['quantity'] * item['price']
        })
        creditInput.value = sum + (parseInt(termInput.value) * sms)

        itemsInput.value = JSON.stringify(items)
    }

    // Добавление новых товаров
    function addInput() {
 items.push({
     name: '',
     price: 0,
     quantity: 1
 })

        var profile = document.getElementById('rowProd');
        var div = document.createElement('div');
        div.classList = 'form-row prod-row ' + 'form-row--' + ++z;
        div.innerHTML = `<input type="text" id="product" oninput = "getProductName(this, z)" placeholder="Товар" class="product-name">
            <input  type="number" id='price_${z}' oninput = "getProductPrice(this, z)" class="product-price" placeholder="Цена" min="3000" max="290000" >
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




    function smsPrice() {
        smsCost =  (parseInt(termInput.value) * sms);
        return smsCost;
    }

    function totalPrice () {
        allPrice =  ((parseInt(termInput.value) * 199) + parseInt(creditInput.value));
    }

    function emailStick () {
        tellFix = tellInput.value.replace(/\D/g,'');
        emailInput = 'Kt' + tellFix + email;
        emailSent.value = emailInput
        return emailInput;
    }

    function monthlyPrice () {
        var monthlyInterest = interest / 100 / 12;
        var x = Math.pow(1 + monthlyInterest, termInput.value);
        var tempCredit = parseInt(creditInput.value) + parseInt(smsCost);
        var monthlyPayment = (tempCredit * x * monthlyInterest) / (x - 1);
        monthlyPayment = monthlyPayment | 0;
        let results = document.getElementById('results-input');
        results.value = monthlyPayment.toLocaleString() + ' Рублей';
    }



    smsPrice();
    totalPrice();
    emailStick();
    monthlyPrice();
    test();



    for (const input of inputs) {
        input.addEventListener('input', function () {
            smsPrice();
            totalPrice();
            emailStick();
            monthlyPrice();
            test();
        })
    }

    prodRow.addEventListener('keyup', function() {
        smsPrice();
        totalPrice();
        emailStick();
        monthlyPrice();
        test();
    })




    setInterval(test, 500);



    document.getElementById('term-range').addEventListener('click', function() {

        let sum = 0
        items.forEach(item => {
            sum += item['quantity'] * item['price']
        })
        creditInput.value = sum + (parseInt(termInput.value) * sms)

        itemsInput.value = JSON.stringify(items)

    })


    btn.addEventListener('click', function () {

        document.querySelectorAll('.product-price').forEach(function (element) {
            if(element.value === '') {
                btn.setAttribute('disabled', 'disabled');
                alert('Введите все поля')
            }
        })
        document.querySelectorAll('.product-quantity').forEach(function (element) {
            if(element.value === '') {
                btn.setAttribute('disabled', 'disabled');
                alert('Введите все поля')
            }
        })
        // alert('Введите все поля')
        // form.submit();
})


</script>
</body>
</html>


{{--TINKOFF_BTN_YELLOW TINKOFF_SIZE_L--}}
