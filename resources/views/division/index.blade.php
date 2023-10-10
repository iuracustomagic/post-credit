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
                    <div class="col-sm-2">
                        <h1 class="m-0">{{$title}}</h1>
                    </div>
                    <div class="col-sm-10 d-flex justify-content-end">
                        <a href="{{route('division.create')}}" class="btn btn-primary mr-5 px-4 fs-5">Добавить ТТ</a>

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
                        <table class="table table-hover text-nowrap" id="crudTable">
                            <thead>
                            <tr>
                                <th>Статус</th>
                                <th>Название</th>
                                <th>Адресс ТТ</th>
                                <th>Тип ТТ</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($divisions as $division)
                                <tr >
                                    <td>
                                        <button class="btn btn-sm {{$division->status == '0' ? 'btn-danger' : 'btn-success'}}">{{$division->statusTitle}}</button>
                                    </td>
                                    <td> {{$division->name}}</td>
                                    <td> {{$division->address}}</td>
                                    <td> {{$division->typeTitle}}</td>


                                    <td class="d-flex">
                                        <div class="mr-2">
                                            <a href="{{route('division.show', $division->id)}}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="mr-2">
                                            <a href="{{route('division.edit', $division->id)}}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                        @if($currentUser->role_id == 1)
                                        <form action="{{route('division.delete', $division->id)}}" method="post" onsubmit="return confirm('Вы действительно хотите удалить?');">
                                            @csrf
                                            @method(('delete'))
                                            <button type="submit" class="btn btn-sm btn-danger">
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
    @endsection

    @push('script')


        <!-- DataTable -->
            <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" type="text/javascript"></script>
            <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js" type="text/javascript"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js" type="text/javascript"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js" type="text/javascript"></script>
            <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js" type="text/javascript"></script>

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
                  "columnDefs": [
                      { "orderable": false, "targets": [4] }
                  ]
              });
              table.buttons( '.export' ).remove();

            </script>

    @endpush
