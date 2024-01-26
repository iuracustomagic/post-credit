@php


    @endphp

@extends('layouts.main')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper pb-4">
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <a href="{{route('sms.list')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>

            </div><!-- /.container-fluid -->
        </div>
    @include('components.flash_message')
    <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row mt-4 ">
                    <form action="{{route('setting.store')}}" method="post" class="w-100" enctype="multipart/form-data">
                    @csrf
                        @method('patch')
                        <div class="row">
                            <div class="form-check col-sm-3 pl-5 mr-4 ">
                                <input class="form-check-input" type="checkbox" {{$settings['first_sms'] == 'on' ? 'checked': ''}} name="first_sms"  id="first_sms">
                                @error('first_sms')<p class="text-danger"> {{$message}}</p>@enderror
                                <label class="form-check-label" for="hide_find_mfo">
                                    Включить первую смс
                                </label>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="message_success">Текст сообщения на согласие на обработку</label>
                                <input type="text" class="form-control" id="message_accept" name="message_accept" placeholder="" value="{{$settings['message_accept']}}">
                                @error('message_accept')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="message_error">Текст сообщения при подписании заявки</label>
                                <input type="text" class="form-control" id="message_success" name="message_success" placeholder="" value="{{$settings['message_success']}}">
                                @error('message_success')<p class="text-danger"> {{$message}}</p>@enderror
                            </div>
                        </div>


                        <div class="form-group text-center">

                            <input type="submit" class="btn btn-primary" id="submitBtn" value="Сохранить" style="width: 200px">

                        </div>
                    </form>
                </div>


            </div><!-- /.container-fluid -->
            {{--@dump($orders)--}}
            {{--@dump($orders['total'])--}}
        </section>
        <!-- /.content -->
        @endsection

        @push('script')


            <script>



            </script>

        @endpush
