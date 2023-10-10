@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-2">
                        <h1 class="m-0">{{$title}}</h1>
                    </div>
                    <div class="col-sm-6 d-flex ">
                        <a href="{{route('manager.create')}}" class="btn btn-primary mr-5 px-4 fs-5">Добавить Менеджера</a>
                        <a href="{{route('bonus.setting')}}" class="btn btn-primary mr-5 px-4 fs-5">Бонусы</a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <table class="table table-hover text-nowrap" id="crudTable">
                            <thead>
                            <tr>
                                <th>Статус</th>
                                <th>Имя Фамилия Отчество</th>
                                <th>Телефон</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr >
                                    <td>
                                        <button class="btn btn-sm {{$user->status == '1' ? 'btn-success' : 'btn-danger'}}">{{$user->statusTitle}}</button>
                                    </td>
                                    <td> {{$user->first_name.' '.$user->last_name.' '.$user->surname}}</td>
                                    <td> {{$user->phone}}</td>

                                    <td class="d-flex">
                                        <div class="mr-2">
                                            <a href="{{route('manager.show', $user->id)}}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="mr-2">
                                            <a href="{{route('manager.edit', $user->id)}}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>

                                        <form action="{{route('manager.delete', $user->id)}}" method="post" onsubmit="return confirm('Вы действительно хотите удалить?');">
                                            @csrf
                                            @method(('delete'))
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

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

    @endsection

    @push('script')


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
                      { "orderable": false, "targets": [3] }
                  ]
              });
              table.buttons( '.export' ).remove();

            </script>

    @endpush
