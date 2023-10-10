@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->


        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4">
                <div class="row mt-4">
                    <form action="{{route('manager.update', $user->id)}}" method="post" class="w-100">
                        @csrf
                        @method('patch')


                        <nav class="w-100">
                            <div class="nav nav-tabs" id="product-tab" role="tablist">
                                <a class="nav-item nav-link active fs-5" id="product-desc-tab" data-toggle="tab" href="#product-desc" role="tab" aria-controls="product-desc" aria-selected="true">Настройки</a>
                                <a class="nav-item nav-link fs-5" id="product-comments-tab" data-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="false">Рефералы</a>
                                <a class="nav-item nav-link fs-5" id="product-rating-tab" data-toggle="tab" href="#product-rating" role="tab" aria-controls="product-rating" aria-selected="false">Бонусы</a>
                            </div>
                        </nav>
                        <div class="tab-content p-3" id="nav-tabContent" style="height: 500px">
                            <div class="tab-pane fade show active" id="product-desc" role="tabpanel" aria-labelledby="product-desc-tab">
                                <div class="row w-75 mt-3">

                                    <div class="row  ">
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

                                    <div class="row mb-5">
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


                                </div>
                            </div>

                            <div class="tab-pane fade" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab">
                                <div class="row w-75 mt-3">
                                    <div class="col-sm-5">
                                        <span>Реф ссылка</span>
                                        <a id="copyBtn" class="" href="javascript:handleCopyBtn()">Скопировать</a>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="my-4">Мои рефералы</h3>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <table class="table table-hover text-nowrap" id="managersTable">
                                                <thead>
                                                <tr>
                                                    <th>Имя Фамилия Отчество</th>
                                                    <th>Телефон</th>
                                                    <th>Заработал</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if($managers)
                                                    @foreach($managers as $manager)
                                                        <tr>
                                                            <td>{{$manager->first_name.' '.$manager->last_name.' '.$manager->surname}}</td>
                                                            <td>{{$manager->phone}}</td>
                                                            <td>{{$manager->creditBonus + $manager->smsBonus}}</td>

                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                                <div class="row my-3">
                                    <div class="col-md-5 d-flex">
                                        <label for="">От</label>
                                        <input type="date" name="date_from" value="{{request()->get('date_from') }}" class="form-control mx-3">
                                        <label for="">До</label>
                                        <input type="date" name="date_to" value="{{request()->get('date_to')}}" class="form-control ml-3">
                                    </div>

                                    <div class="col-md-5">
                                        <button type="submit" name="search" class="btn btn-primary mr-3">Поиск</button>
                                        <a class="btn btn-outline-dark btn-sm" href="{{route('manager.edit', $user->id)}}">Очистить</a>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-info">
                                            <div class="inner">
                                                <p>Бонусы за кредиты</p>
                                                <h3>{{$user->creditBonus}} руб</h3>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-success">
                                            <div class="inner">
                                                <p>Бонусы за СМС</p>
                                                <h3>{{$user->smsBonus}} руб</h3>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-warning">
                                            <div class="inner">
                                                <p>Бонусы за рефералов</p>
                                                <h3>{{$user->refBonus}} руб</h3>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- ./col -->
                                    <div class="col-lg-3 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                                <p>Бонусы за подбор кредита</p>
                                                <h3>{{$user->creditChoose}} руб</h3>

                                            </div>

                                        </div>
                                    </div>
                                    <!-- ./col -->
                                </div>

                            </div>


                        </div>
                        <div class="form-group text-center">
                            <input type="submit" name="save"  class="btn btn-primary" value="Сохранить">
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
                    const randomNumber = '{{$user->ref_number}}'

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
