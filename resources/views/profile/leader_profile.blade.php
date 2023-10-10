@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

    @include('components.flash_message')
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4">
                <div class="row ">
                    <form action="{{route('manager.update', $user->id)}}" method="post" class="w-100">
                        @csrf
                        @method('patch')


                        <div class="row w-75 mt-4">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->first_name}}" name="first_name" placeholder="Имя">
                                @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->last_name}}" name="last_name" placeholder="Фамилия">
                                @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->surname}}" name="surname" placeholder="Отчество">
                                @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                        </div>

                        <div class="row w-75 mb-5">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->phone}}" name="phone" placeholder="Телефон">
                                @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->email}}" name="email" placeholder="Почта">
                                @error('phone')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <input type="text" class="form-control" value="{{$user->login}}" name="login" placeholder="Логин">
                                @error('login')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <input type="password" class="form-control" name="password" placeholder="Пароль">
                            </div>
                        </div>


                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-primary" value="Сохранить">
                        </div>

                    </form>
                </div>




            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        @endsection

        @push('script')
            <script>
                function handleCopyBtn() {
                    const server = "{{$_SERVER['HTTP_HOST']}}";
                    const randomNumber = generateRandomString(8)

                    const copyValue = `http://${server}/register/?refid=${randomNumber}`;

                    const area = document.createElement('textarea');
                    document.body.appendChild(area);
                    area.value = copyValue;
                    area.select();
                    document.execCommand("copy");
                    document.body.removeChild(area);
                    Toast.fire({
                        icon: 'info',
                        title: 'Реф. ссылка скопированна.'
                    })
                }

                const table = $('#managersTable').DataTable({
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

                });
                table.buttons( '.export' ).remove();
            </script>
    @endpush
