@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('manager.index')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Создать Менеджера</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4">
                <div class="row mt-4">
                    <form action="{{route('manager.store')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" class="form-control" name="role_id" value="2" >
                        <input type="hidden" class="form-control" name="ref_number" id="ref_number">

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
                                            <input type="text" class="form-control" name="last_name" onblur = "myBlurFunction(this)" placeholder="Фамилия" value="{{old('last_name')}}">
                                            @error('last_name')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="first_name" placeholder="Имя" value="{{old('first_name')}}">
                                            @error('first_name')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>

                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="surname" placeholder="Отчество" value="{{old('surname')}}">
                                            @error('surname')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>

                                    <div class="row mb-5">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="phone" placeholder="Телефон" value="{{old('phone')}}">
                                            @error('phone')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="email" placeholder="Почта" value="{{old('email')}}">
                                            @error('email')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <input type="text" class="form-control" name="login"  id="login" placeholder="Логин" value="{{old('login')}}">
                                            @error('login')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Пароль">
                                            @error('password')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <input class="btn btn-outline-success" id="copyBtn" style="display: none" onclick="handleCopyLoginBtn()" value="Скопировать" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="exampleSelectBorder1">Статус</label>
                                            <select class="custom-select form-control" name="status" id="exampleSelectBorder1" >
                                                <option value="1" selected>Активный</option>
                                                <option value="0">Не активный</option>

                                            </select>
                                        </div>
                                    </div>
                                <div class="form-group col-sm-4" data-select2-id="69">
                                    <label>Привязать к менеджеру</label>
                                    <select name="manager_id" class="form-control select2 select2-hidden-accessible" style="width: 100%;" data-select2-id="0" tabindex="-1" aria-hidden="true">
                                        <option data-select2-id="0" value="0">Не привязывать к менеджеру</option>
                                        @foreach($managers as $manager)
                                            <option data-select2-id="{{$manager->id}}" value="{{$manager->id}}">{{$manager->first_name.' '.$manager->last_name}}</option>
                                        @endforeach

                                    </select>

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


                      </div>
                      <div class="tab-pane fade" id="product-rating" role="tabpanel" aria-labelledby="product-rating-tab">
                          <div class="row">
                              <div class="col-lg-3 col-6">
                                  <!-- small box -->
                                  <div class="small-box bg-info">
                                      <div class="inner">
                                          <p>Бонусы за кредиты</p>
                                          <h3>0 руб</h3>

                                      </div>

                                  </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-3 col-6">
                                  <!-- small box -->
                                  <div class="small-box bg-success">
                                      <div class="inner">
                                          <p>Бонусы за СМС</p>
                                          <h3>0 руб</h3>

                                      </div>

                                  </div>
                              </div>
                              <!-- ./col -->
                              <div class="col-lg-3 col-6">
                                  <!-- small box -->
                                  <div class="small-box bg-warning">
                                      <div class="inner">
                                          <p>Бонусы за рефералов</p>
                                          <h3>0 руб</h3>

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
                                      <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$smsRate->reward}}" name="sms" >
                                      @error('sms')<p class="text-danger"> {{$message}}</p>@enderror
                                      <span>%</span>
                                  </div>
                              </div>
                              <div class="form-group col-sm-3">
                                  <div class="d-flex  align-items-center">
                                      <span>С рефералов</span>
                                      <input type="text" style="width: 50px" class="form-control ml-3 mr-2" value="{{$referralRate->reward}}" name="referral" >
                                      @error('referral')<p class="text-danger"> {{$message}}</p>@enderror
                                      <span>%</span>
                                  </div>
                              </div>

                          </div>

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

                const refNumber = document.getElementById('ref_number')
                const firstName = document.getElementById('first_name')
                const login = document.getElementById('login')
                const password = document.getElementById('password')
                const copyBtn =document.getElementById('copyBtn');

                function myBlurFunction(input) {

                    const nameValue = input.value.toLowerCase() + generateRandomString(2)

                    login.value = transliter(nameValue)
                    password.value = generateRandomString(5, true)
                    copyBtn.style.display = 'block';

                }

              $('.select2').select2()

                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                })

                function handleCopyLoginBtn() {
                    const loginValue = login.value
                    const passwordValue = password.value
                    const copyValue = `логин: ${loginValue}, пароль: ${passwordValue}`;

                    const area = document.createElement('textarea');
                    document.body.appendChild(area);
                    area.value = copyValue;
                    area.select();
                    document.execCommand("copy");
                    document.body.removeChild(area);
                    Toast.fire({
                        icon: 'info',
                        title: 'Логин пароль скопированы.'
                    })

                }

              function handleCopyBtn() {
                  const server = "{{$_SERVER['HTTP_HOST']}}";
                  const randomNumber = generateRandomString(8)
                  refNumber.value = randomNumber;
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

            </script>
    @endpush
