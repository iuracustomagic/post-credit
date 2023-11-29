<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
{{--    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />--}}
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

{{--    <link rel="stylesheet" href="{{asset('css/normalize.css')}} ">--}}
{{--    <link rel="stylesheet" href="{{asset('css/style.css')}} ">--}}

    <title>Отправление заявки</title>

{{--    <script src="https://forma.tinkoff.ru/static/onlineScript.js"></script>--}}
</head>
<body class="container pt-5">
<div>
    <a href="{{route('order.createMfo')}}" class="btn btn-secondary" >
        <i class="fas fa-backward mr-2"></i> Назад
    </a>
</div>
<form action="https://paylate.ru/bypartner" name="mfoForm" id="mfoForm" target="_blank" class="d-flex justify-content-center " method="post">



    <input type="hidden" name="client_id" value="{{$post['client_id']}}" />
    <input type="hidden" name="order_id" value="{{$post['order_id']}}" />
{{--    <input type="hidden" name="category" value="Мобильные устройства" />--}}
    <input type="hidden" id="goods" name="goods" value="" />
    <input type="hidden" name="token" value="{{$post['token']}}" />
    <input type="hidden" name="result_url" value="{{$post['result_url']}}" />
    <input type="hidden" name="fio" value="{{$post['fio']}}" />
    <input type="hidden" name="fio1" value="{{$post['fio1']}}" />
    <input type="hidden" name="fio2" value="{{$post['fio2']}}" />
    <input type="hidden" name="action" value="by_partner" />
    <input type="submit" class="btn btn-success" name="submit_button" value="Передать" />
</form>
<script>
    window.onload = function() {
        const goods = document.getElementById('goods')
        const goodsDb = '{{$post['goods']}}';
        console.log(goodsDb)
        goods.value = goodsDb
        if(goodsDb) {
            document.getElementById("mfoForm").submit();
            // window.location.href = '/order-mfo'
        }

    }
</script>
</body>
</html>
