@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('manager.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад</a>
                    </a>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{$user->name}}</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <a href="{{route('manager.edit', $user->id)}}" class="btn btn-primary">Редактировать</a>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>Статус</th>
                                        <th>Имя</th>
                                        <th>Фамилия</th>
                                        <th>Отчество</th>
                                        <th>Логин</th>

                                        <th>Телефон</th>
                                        <th>Менеджер</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>{{$user->statusTitle}}</td>
                                            <td> {{$user->first_name}}</td>
                                            <td> {{$user->last_name}}</td>
                                            <td> {{$user->surname}}</td>
                                            <td> {{$user->login}}</td>
                                            <td> {{$user->phone}}</td>
                                            <td> {{($manager ? $manager->first_name : '-').' '.($manager ? $manager->last_name :'-')}}</td>

                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

@endsection
