@php


    @endphp

@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pb-4">
    {{--            <div class="row ">--}}
    {{--                <div class="col-sm-6">--}}
    {{--                    <h3 class="m-3">Статистика</h3>--}}
    {{--                </div><!-- /.col -->--}}
    {{--            </div>--}}

    <!-- Main content -->
        <section class="content">
            <div class="container-fluid pt-4">

                <div class="row ps-4">
                    <div class="col-2">
                        <a class="btn btn-outline-primary" href="{{route('sms.settings')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                            </svg>
                        </a>

                    </div>
                </div>
                <!-- Small boxes (Stat box) -->
{{--                <div class="row">--}}
{{--                    <div class="col-lg-3 col-6">--}}
{{--                        <!-- small box -->--}}
{{--                        <div class="small-box bg-info">--}}
{{--                            <div class="inner">--}}
{{--                                <p>Всего заявок</p>--}}
{{--                                <h3>{{count($orders)}}</h3>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- ./col -->--}}
{{--                    <div class="col-lg-3 col-6">--}}
{{--                        <!-- small box -->--}}
{{--                        <div class="small-box bg-success">--}}
{{--                            <div class="inner">--}}
{{--                                <p>Подписано</p>--}}
{{--                                <h3>{{count($signed)}}</h3>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- ./col -->--}}
{{--                    <div class="col-lg-3 col-6">--}}
{{--                        <!-- small box -->--}}
{{--                        <div class="small-box bg-warning">--}}
{{--                            <div class="inner">--}}
{{--                                <p>Одобрено</p>--}}
{{--                                <h3>{{count($approved)}}</h3>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- ./col -->--}}
{{--                    <div class="col-lg-3 col-6">--}}
{{--                        <!-- small box -->--}}
{{--                        <div class="small-box bg-danger">--}}
{{--                            <div class="inner">--}}
{{--                                <p>Отказано</p>--}}
{{--                                <h3>{{count($rejected)}}</h3>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <!-- ./col -->--}}

{{--                </div>--}}
                <!-- /.row -->
                <div class="row pt-5">
                    <div class="col-12">
                        @include('components.flash_message')
                        <table class="table table-hover text-nowrap w-100 " id="smsTable" data-order='[[ 2, "desc" ]]'>
                            <thead>
                            <tr>
                                <th>Статус</th>
                                <th>ФИО</th>
                                <th>Дата</th>
                                <th>Телефон</th>
                                <th>Текст смс</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($smsList as $sms)
                                <tr >

                                    <td> {{$sms->statusTitle}}</td>
                                    <td> {{$sms->user}}</td>
                                    <td> {{$sms->created_at}}</td>
                                    <td> {{$sms->phone}}</td>
                                    <td> {{$sms->message_text}}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div id="bottom_buttons" class="d-print-none text-center text-sm-left mb-4">
                    <div class="pull-left">

                        <ul class="ul-dropdown">
                            <li class="firstli">
                                <i class="la la-download"></i><a href="#">Экспорт</a>
                                <ul class="ul-export">
                                    <li>Export CSV</li>
                                    <li>Export Excel</li>
                                    <li>Export PDF</li>
                                    <li>Print</li>
                                </ul>
                            </li>
                        </ul>

                    </div>
                    <div class="pull-right">

                        <ul class="ul-dropdown">
                            <li class="secondli">
                                <i class="la la-eye-slash mr-2"></i><a href="#">Видимость колонок</a>
                                <ul class="ul-choose">
                                    <li data-id="0">Статус</li>
                                    <li data-id="1">ФИО</li>
                                    <li data-id="2">Дата</li>
                                    <li data-id="3">Телефон</li>
                                    <li data-id="4">Текст смс</li>

                                </ul>
                            </li>
                        </ul>

                    </div>
                </div>
            </div><!-- /.container-fluid -->
            {{--@dump($orders)--}}
            {{--@dump($orders['total'])--}}
        </section>
        <!-- /.content -->
        @endsection

        @push('script')


            <script>

                $('.select2').select2()

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })
                {{--let orders =JSON.parse({{$orders}})--}}
                //     console.log(orders)
                $(".ul-export li").click(function() {
                    var i = $(this).index() + 1
                    var table = $('#smsTable').DataTable();
                    if (i == 1) {
                        table.button('.buttons-csv').trigger();
                    } else if (i == 2) {
                        table.button('.buttons-excel').trigger();
                    } else if (i == 3) {
                        table.button('.buttons-pdf').trigger();
                    } else if (i == 4) {
                        table.button('.buttons-print').trigger();
                    }
                });
                $(".ul-choose li").click(function() {
                    $( this ).toggleClass( "not-export-col" );
                    const text =  $( this ).text();
                    const id =  $( this ).data( "id" );
                    $('#smsTable thead tr:first').each(function() {

                        $(this).find("th").eq(id).toggleClass( "not-export-col" );

                    });
                    $('#smsTable tbody tr').each(function() {

                        $(this).find("td").eq(id).toggleClass( "not-export-col" );

                    });

                });
                $('#smsTable').DataTable({
                    "language": {
                        "lengthMenu": "_MENU_  записей на странице",
                        "info": "Показано _START_ до _END_ из _TOTAL_ совпадений",
                        "search": "Поиск:",
                        "paginate": {
                            "first": "First",
                            "last": "Last",
                            "next": ">",
                            "previous": "<"
                        }
                    },
                    // order: [[4, 'desc']],
                    buttons: [
                        {
                            text: 'csv',
                            extend: 'csvHtml5',
                            messageTop: 'Заказы',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)'
                            }
                        },
                        {
                            text: 'excel',
                            extend: 'excelHtml5',
                            messageTop: 'Заказы',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)'
                            }
                        },
                        {
                            text: 'pdf',
                            extend: 'pdfHtml5',
                            messageTop: 'Заказы',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)'
                            }
                        },
                        {
                            text: 'print',
                            extend: 'print',
                            messageTop: 'Заказы',
                            exportOptions: {
                                columns: ':visible:not(.not-export-col)'
                            }
                        },
                    ],
                    // columnDefs: [{
                    //     orderable: false,
                    //     targets: -1
                    // }]
                });


            </script>

        @endpush
        @section('after_styles')
            <style>
                table {
                    display: block;
                    overflow-x: auto;
                    white-space: nowrap;
                }

                .table td, .table th{
                    vertical-align: middle !important;
                }
                .table td table {
                    width: 100%;
                }
                .table thead {
                    width: 100%;
                }

                tr.may-hide{
                    position: relative;
                }
                tr.may-hide.hidden > td > table{
                    display: none;
                }
                tr.may-hide > td {
                    padding: 0;
                }
                table.lvl:before{
                    content: "";
                    position: absolute;
                    height: 100%;
                    z-index: 1;
                }
                table.lvl-0:before{
                    border-left: 1px solid #7c69ef;
                    left: 20px;
                }

                table.lvl-1:before{
                    border-left: 1px solid #00a65a;
                    left: 40px;
                }

                table.lvl-2:before{
                    border-left: 1px solid #1b2a4e;
                    left: 60px;
                }
                .own.o-lvl-3:before{
                    border-color: #1b2a4e;
                    left: 60px;
                }
                td.controls{
                    position: relative;
                    width: 86px;
                    padding: .75rem 10px;
                }
                td.index{
                    width: 50px;
                }
                td.prof{
                    width: 20%;
                }
                td.employee{
                    width: 10%;
                }
                td.date{
                    width: 120px;
                    text-align: center;
                }
                td.available,
                td.passed{
                    width: 110px;
                    text-align: center;
                }
                .ev-list p{
                    margin: 0
                }
                .ev-list button{
                    float: right;
                }
                td.avg{
                    width: 130px;
                    text-align: center;
                }
                td.res{
                    width: 90px;
                    text-align: center;
                }
                td.supervisor{
                    width: 10%;
                }
                .table-danger > td{
                    background-color: rgba(214, 48, 49, .4) !important;
                }
                .table-warning > td{
                    background-color: rgba(253, 203, 110, .4) !important;
                }
                .table-success > td{
                    background-color: rgba(0, 184, 148, .4) !important;
                }


                /*-------------------------------*/
                .pull-left ul,
                .pull-right ul {
                    list-style: none;
                    margin: 0;
                    padding-left: 0;
                }
                .pull-left a,
                .pull-right a{
                    text-decoration: none;
                    color: #ffffff;
                }
                .pull-left li,
                .pull-right li{
                    color: #ffffff;
                    background-color: #456e9a;
                    border-color: #456e9a;
                    display: block;
                    float: left;
                    position: relative;
                    text-decoration: none;
                    transition-duration: 0.5s;
                    padding: 10px 30px;
                    font-size: .75rem;
                    font-weight: 400;
                    line-height: 1.428571;
                }
                .pull-left li:hover,
                .pull-right li:hover {
                    cursor: pointer;
                    color: #00bb00;
                }
                .pull-left li a:hover,
                .pull-right li a:hover {
                    color: #00bb00;
                }
                .pull-left ul li ul {
                    visibility: hidden;
                    opacity: 0;
                    min-width: 8.2rem;
                    position: absolute;

                    transition: all 0.5s ease;
                    margin-top: 8px;
                    left: 0;
                    bottom: 34px;
                    display: none;
                }
                .pull-right ul li ul {
                    visibility: hidden;
                    opacity: 0;
                    min-width: 10.2rem;
                    position: absolute;
                    z-index: 1000;
                    transition: all 0.5s ease;
                    margin-top: 8px;
                    left: 0;
                    bottom: 34px;
                    display: none;
                }
                .pull-left ul li:hover>ul,
                .pull-left ul li ul:hover,
                .pull-right ul li:hover>ul {
                    visibility: visible;
                    opacity: 1;
                    display: block;
                }
                .pull-left ul li ul li,
                .pull-right ul li ul li  {
                    clear: both;
                    width: 100%;
                    color: #ffffff;
                }

                .ul-choose li.not-export-col {
                    background-color: white;
                    color: #0e111c;
                }
                .ul-dropdown {
                    margin: 0.3125rem 1px !important;
                    outline: 0;
                }
                .firstli {
                    border-radius: 0.2rem;
                    margin-bottom: 20px;
                    margin-right: 25px;
                }
                .firstli i {
                    position: relative;
                    display: inline-block;
                    top: 0;
                    margin-top: -1.1em;
                    margin-bottom: -1em;
                    font-size: 0.8rem;
                    vertical-align: middle;
                    margin-right: 5px;
                }
                .table tr th.not-export-col {
                    display: none;
                }
                .table tr td.not-export-col {
                    display: none;
                }
                .dt-buttons {
                    display: none;
                }

                /* print styles */
                @media print {
                    .evaluations_container {
                        display: flex;
                    }
                    p {
                        margin-right: 10px;

                    }

                }
            </style>
@endsection
