@php


@endphp

@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pb-4">


        <!-- Main content -->
        <section class="content">
            <div class="">
                <form action="" method="GET" class="py-3">
                    <div class="row">
                        <div class="col-md-3 d-flex">
                            <label for="">От</label>
                            <input type="date" name="date_from" value="{{request()->get('date_from') }}" class="form-control mx-3">
                            <label for="">До</label>
                            <input type="date" name="date_to" value="{{request()->get('date_to')}}" class="form-control ml-3">
                        </div>
                        <div class="col-md-3">
                            <select name="status" id="" class="form-select">
                                <option value="">Выберите статус</option>
                                <option value="new" {{request()->get('status') == 'new' ? 'selected':''}}>Новая</option>
                                <option value="inprogress" {{request()->get('status') == 'inprogress' ? 'selected':''}}>В процессе</option>
                                <option value="approved" {{request()->get('status')== 'approved' ? 'selected':''}}>Одобрена</option>
                                <option value="signed" {{request()->get('status') == 'signed' ? 'selected':''}}>Подписана</option>
                                <option value="canceled" {{request()->get('status')== 'canceled' ? 'selected':''}}>Отменена</option>
                                <option value="rejected" {{request()->get('status')== 'rejected' ? 'selected':''}}>Отказ</option>
                            </select>
                        </div>
                        <div class="col-md-3">

                            <select name="company" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="" tabindex="-1" aria-hidden="true">
                                <option value="">Название Юр. лица</option>
                                @foreach($companies as $division)
                                    <option data-select2-id="{{$division->id}}" value="{{$division->id}}">{{$division->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary mr-3">Поиск</button>
                            <a class="btn btn-outline-dark btn-sm" href="{{url('/')}}">Очистить</a>
                        </div>

                    </div>

                </form>


                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <p>Всего заявок</p>
                                <h3>{{count($orders)}}</h3>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <p>Подписано</p>
                                <h3>{{count($signed)}}</h3>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <p>Одобрено</p>
                                <h3>{{count($approved)}}</h3>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <p>Отказано</p>
                                <h3>{{count($rejected)}}</h3>
                            </div>

                        </div>
                    </div>
                    <!-- ./col -->

                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover text-nowrap" id="orderTable">
                            <thead>
                            <tr>
                                <th>Статус</th>
                                <th>ФИО</th>
                                <th>Дата</th>
                                <th>Название</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr >
                                    <td>
                                        {{$order->statusTitle}}
                                    </td>
                                    <td>
                                        {{$order->first_name.' '.$order->last_name.' '.$order->surname}}
                                    </td>
                                    <td> {{$order->created_at}}</td>
                                    <td> {{$order->companyName}}/{{$order->divisionAddress}}</td>

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

                </div>
            </div><!-- /.container-fluid -->

        </section>
        <!-- /.content -->
@endsection

        @push('script')


            <script>

                $('.select2').select2()

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                $(".ul-export li").click(function() {
                    var i = $(this).index() + 1
                    var table = $('#orderTable').DataTable();
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

                const table = $('#orderTable').DataTable({
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
                    dropup: true,
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
                    columnDefs: [{
                        orderable: false,
                        targets: -1
                    }
                       ],

                });
                // table.buttons( '.export' ).remove();

            </script>

    @endpush
@section('after_styles')
            <style>
                .own:before{
                    content: "";
                    width: 13px;
                    border-bottom: 1px solid #ccc;
                    position: absolute;
                    top: 50%;
                }
                .table_container {
                    position: relative;
                    min-height: 300px;
                }
                .evaluation_title {
                    position: absolute;
                    top: 44px;
                    left: 0;
                    color: #366a8d;
                    font-style: italic;

                }
                .table td, .table th{
                    vertical-align: middle !important;
                }
                .table td table {
                    width: 100%;
                }
                .toggler{
                    display: block;
                    width: 20px;
                    height: 20px;
                    border: 1px solid #ccc;
                    text-align: center;
                    line-height: 18px;
                    /*position: absolute;*/
                    /*top: calc(50% - 10px);*/
                    /*bottom: 0;*/
                    left: -10px;
                    background-color: #fff;
                    z-index: 2;
                    cursor: pointer;
                }
                .toggler.revealed:before{
                    content: "";
                    position: absolute;
                    border-left: 1px solid #ccc;
                    height: 50%;
                    z-index: 1;
                    top: calc(50% + 10px);
                }
                .toggler.t-lvl-0,
                .toggler.t-lvl-0:before{
                    border-color: #7c69ef;
                }
                .toggler.t-lvl-1{
                    border-color: #00a65a;
                    margin-left: 20px;
                }
                .toggler.t-lvl-1:before{
                    border-color: #00a65a;
                }
                .toggler.t-lvl-2{
                    border-color: #1b2a4e;
                    margin-left: 40px;
                }
                .toggler.t-lvl-2:before{
                    border-color: #1b2a4e;
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
                .own.o-lvl-1:before{
                    border-color: #7c69ef;
                    left: 20px;
                }
                table.lvl-1:before{
                    border-left: 1px solid #00a65a;
                    left: 40px;
                }
                .own.o-lvl-2:before{
                    border-color: #00a65a;
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
                .table-perfect{
                    background-color: rgba(129, 236, 236, .4) !important;
                }
                .filter{
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                /*-------------------------------*/

                .pull-left ul {
                    list-style: none;
                    margin: 0;
                    padding-left: 0;
                }
                .pull-left a {
                    text-decoration: none;
                    color: #ffffff;
                }
                .pull-left li
                {
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
                .pull-left li:hover
                {
                    cursor: pointer;
                    color: #00bb00;
                }
                .pull-left li a:hover
                {
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

                .pull-left ul li:hover>ul,
                .pull-left ul li ul:hover  {
                    visibility: visible;
                    opacity: 1;
                    display: block;
                }
                .pull-left ul li ul li
                {
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
