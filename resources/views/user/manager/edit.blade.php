@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
{{--                    <div>--}}
{{--                        <a href="{{route('manager.index')}}" class="btn btn-secondary" >--}}
{{--                            <i class="fas fa-backward mr-2"></i> Назад--}}
{{--                        </a>--}}
{{--                    --}}
{{--                    </div>--}}
                    <div class="col-sm-6">
                        <h1 class="m-0">Редактировать менеджера</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    @include('components.flash_message')
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4">
                <div class="mb-4">
                    <a href="{{route('manager.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <div class="row w-75">
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

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="exampleSelectBorder">Статус</label>
                                <select class="custom-select form-control" name="status" id="exampleSelectBorder" >
                                    <option {{$user->status == '1' ? 'selected' : ''}} value="1">Активный</option>
                                    <option {{$user->status == '0' ? 'selected' : ''}} value="0">Не активный</option>

                                </select>
                            </div>
                        </div>
                                <div class="form-group col-sm-4" data-select2-id="69">
                                    <label>Привязать к менеджеру</label>
                                    <select name="manager_id" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="{{$manager_id}}" tabindex="-1" aria-hidden="true">
                                        <option value="0">Выберите менеджера</option>
                                        @foreach($managers as $manager)
                                            <option {{$manager->id == $manager_id ? 'selected' : ''}} data-select2-id="{{$manager->id}}" value="{{$manager->id}}">{{$manager->first_name.' '.$manager->last_name}}</option>
                                        @endforeach

                                    </select>

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
                                                @if($myManagers)
                                                    @foreach($myManagers as $myManager)
                                                        <tr>
                                                            <td>{{$myManager->first_name.' '.$myManager->last_name.' '.$myManager->surname}}</td>
                                                            <td>{{$myManager->phone}}</td>
                                                            <td>{{$myManager->creditBonus + $myManager->smsBonus}}</td>

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

                                <h2 class="mb-3">Настройки бонусов</h2>
                                <h4 class="mb-3">За кредиты</h4>
                                <div class="row  mt-3 mb-3">

                                    @foreach($creditRates as $creditRate)
                                        <div class="form-group col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <span>{{$creditRate->value}} %</span>
                                                <input type="text" inputmode="decimal" pattern="[0-9]*[.]*[0-9]*" style="width: 50px" class="form-control ml-3 mr-2" value="{{$creditRate->reward}}" name="credit_{{$creditRate->id}}" >

                                                <span>%</span>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="row  mt-3 mb-3">

                                    <div class="form-group col-sm-3">
                                        <div class="d-flex align-items-center">
                                            <span>За смс</span>
                                            <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$smsRate}}" name="sms" >
                                            @error('sms')<p class="text-danger"> {{$message}}</p>@enderror
                                            <span>%</span>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <div class="d-flex  align-items-center">
                                            <span>С рефералов</span>
                                            <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$referralRate}}" name="referral" >
                                            @error('referral')<p class="text-danger"> {{$message}}</p>@enderror
                                            <span>%</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="form-group text-center">
                            <input type="submit" name="save" class="btn btn-primary" value="Сохранить">
                        </div>

                    </form>

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




                function handleCopyBtn() {
                    const server = "{{$_SERVER['HTTP_HOST']}}";
                    const randomNumber = '{{$user->ref_number}}';

                    const copyValue = `https://${server}/register/?refid=${randomNumber}`;

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
