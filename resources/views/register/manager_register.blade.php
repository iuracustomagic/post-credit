<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Log in</title>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/fontawesome-free/css/all.min.css')}} ">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('adminlte/dist/css/adminlte.min.css')}}">
</head>
<body class=" login-page vh-100">
    <!-- Content Wrapper. Contains page content -->

        <!-- Content Header (Page header) -->

        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid ml-4">
                <div class="row mt-4 d-flex justify-content-center align-items-center">


                    <form action="{{route('manager.new')}}" method="post" class="w-100">
                        @csrf
                        <input type="hidden" class="form-control" name="role_id" value="2" >
                        <input type="hidden" class="form-control" name="manager_id" value="{{rand(1000,9999)}}" id="ref_number">
                        <input type="hidden" class="form-control" name="status" value="1" id="ref_number">
                        <input type="hidden" class="form-control" name="ref_number" value="{{request()->get('refid')}}" id="manager_id">

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

                        </div>


                        <div class="form-group text-center">
                            <input type="submit" class="btn btn-primary" value="Сохранить">
                        </div>

                    </form>
                </div>




            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        <!-- Bootstrap 4 -->
        <script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('adminlte/dist/js/adminlte.js')}}"></script>
        </body>
</html>


{{--        @push('script')--}}
{{--            <script>--}}

{{--                const refNumber = document.getElementById('ref_number')--}}
{{--                const firstName = document.getElementById('first_name')--}}
{{--                const login = document.getElementById('login')--}}
{{--                const password = document.getElementById('password')--}}
{{--                const copyBtn =document.getElementById('copyBtn');--}}

{{--                function myBlurFunction(input) {--}}

{{--                    const nameValue = input.value.toLowerCase() + generateRandomString(2)--}}

{{--                    login.value = transliter(nameValue)--}}
{{--                    password.value = generateRandomString(5, true)--}}
{{--                    copyBtn.style.display = 'block';--}}

{{--                }--}}

{{--              $('.select2').select2()--}}

{{--                $('.select2bs4').select2({--}}
{{--                    theme: 'bootstrap4'--}}
{{--                })--}}

{{--                function handleCopyLoginBtn() {--}}
{{--                    const loginValue = login.value--}}
{{--                    const passwordValue = password.value--}}
{{--                    const copyValue = `логин: ${loginValue}, пароль: ${passwordValue}`;--}}

{{--                    const area = document.createElement('textarea');--}}
{{--                    document.body.appendChild(area);--}}
{{--                    area.value = copyValue;--}}
{{--                    area.select();--}}
{{--                    document.execCommand("copy");--}}
{{--                    document.body.removeChild(area);--}}
{{--                    Toast.fire({--}}
{{--                        icon: 'info',--}}
{{--                        title: 'Логин пароль скопированы.'--}}
{{--                    })--}}

{{--                }--}}

{{--              function handleCopyBtn() {--}}
{{--                  const server = "{{$_SERVER['HTTP_HOST']}}";--}}
{{--                  const randomNumber = generateRandomString(8)--}}
{{--                  refNumber.value = randomNumber;--}}
{{--                  const copyValue = `http://${server}/register/?refid=${randomNumber}`;--}}

{{--                  const area = document.createElement('textarea');--}}
{{--                  document.body.appendChild(area);--}}
{{--                  area.value = copyValue;--}}
{{--                  area.select();--}}
{{--                  document.execCommand("copy");--}}
{{--                  document.body.removeChild(area);--}}
{{--                  Toast.fire({--}}
{{--                      icon: 'info',--}}
{{--                      title: 'Реф. ссылка скопированна.'--}}
{{--                  })--}}
{{--              }--}}

{{--            </script>--}}
{{--    @endpush--}}
