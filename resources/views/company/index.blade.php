@php
    use Illuminate\Support\Facades\Auth;

    $currentUser = Auth::user();
@endphp

@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 mt-2">
                    <div class="col-sm-4">
                        <h1 class="m-0">{{$title}}</h1>
                    </div>

                    <div class="col-sm-8 d-flex justify-content-end align-items-start">
                        <a href="{{route('company.create')}}" class="btn btn-primary mr-5 px-4 fs-5">Добавить Юр. лицо</a>
                        @if($currentUser->role_id == 1)
                        <a href="{{route('company.new')}}" class="btn btn-primary mr-5 px-4 fs-5">Новые</a>
                         @endif
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    @include('components.flash_message')
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover text-nowrap" id="crudTable" style="min-height: 200px">
                            <thead>
                            <tr>
                                <th>Статус</th>
                                <th>Название</th>
                                <th>Менеджер</th>
                                <th>Торговые точки</th>
                                <th>Продавцы</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr >
                                    <td>
                                        <button class="btn btn-sm {{$company->status == '0' ? 'btn-danger' : 'btn-success'}}">{{$company->statusTitle}}</button>
                                    </td>
                                    <td> {{$company->name}}</td>
                                    <td> {{$company->managerName}}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{count($company->divisions)}}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{route('companyDivisions.show', $company->id)}}">Показать все</a></li>
                                                    <li><a class="dropdown-item" href="{{route('companyDivisions.add', $company->id)}}">Добавить ТТ</a></li>
                                                </ul>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <div class="" >
                                            <div class="btn-group" role="group">
                                                <a class="btn btn-outline-secondary" href="{{route('showSalesmen', $company->id)}}">{{count($company->salesmen)}}</a>

                                            </div>
                                        </div>


                                    </td>



                                    <td class="d-flex">
                                        <div class="mr-2 my-2">
                                            <a href="{{route('company.show', $company->id)}}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                        @if($currentUser->role_id == 1 || $currentUser->role_id == 2 && $company->status == 1)
                                        <div class="mr-2 my-2">
                                            <a href="{{route('company.edit', $company->id)}}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                        @endif
                                    @if($currentUser->role_id == 1)
                                        <form action="{{route('company.delete', $company->id)}}" method="post" onsubmit="return confirm('Вы действительно хотите удалить?');">
                                            @csrf
                                            @method(('delete'))
                                            <button type="submit" class="btn btn-sm btn-danger my-2">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
@endsection

    @section('after_styles')
            <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/dist/css/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/dist/css/datatables.net-fixedheader-bs4/css/fixedHeader.bootstrap4.min.css') }}">
            <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/dist/css/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">

            <style>
                .divisionModal {
                    position: absolute;
                    bottom: -80px;
                    left: 15px;
                    width: 100px;
                    height: 80px;
                    display: flex;
                    flex-direction: column;
                    background-color: #faf3f3;
                }
            </style>
    @endsection

    @push('script')


{{--        <!-- DataTable -->--}}
{{--            <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js" type="text/javascript"></script>--}}
{{--            <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js" type="text/javascript"></script>--}}

            <script>
              const table = $('#crudTable').DataTable({
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

                  aoColumns : [
                      { "sWidth": "10%"},
                      { "sWidth": "25%"},
                      { "sWidth": "20%"},
                      { "sWidth": "15%"},
                      { "sWidth": "15%"},
                      { "sWidth": "15%"},
                  ],
                  "columnDefs": [
                      { "orderable": false, "targets": [5] },
                      // { "width": "10%", "targets": 1 }
                  ]
              });
              table.buttons( '.export' ).remove();


            </script>

    @endpush
