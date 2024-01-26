@extends('layouts.order')

@section('content')
    <div style="padding-top:50px; padding-bottom: 80px">
        @include('components.flash_message')
        <form action="{{route('order.sendMoreData')}}"
{{--              onsubmit="return login();" --}}
              id="form" method="post" class="w-100" enctype="multipart/form-data">
            @csrf
            <input name="application_id" value="{{$order->application_id}}" type="hidden">


            <div class="container">
            <div style="display: flex; justify-content: space-between; padding-right: 50px; padding-left: 50px">
                <div>
                    <a href="{{route('statistic.mfo')}}" class="btn btn-secondary" >
                        <i class="fas fa-backward mr-2"></i> Назад
                    </a>

                </div>

            </div>
            <h2 class="form-title">Отправить дополнительные данные в МФО</h2>
            <div class="form-wrap">
                @if($additional->repeat_cause)
                <div class="form_block mb-4 text-center">
                    <span class="h3">Причина перезапроса данных - </span>
                    <span class="h3">{{$additional->causeTitle}} </span>
                </div>
                @endif

                    @if($additional->passport)
                        <div class="form_block mb-4">
                            <div class="form_head">
                                <h3 class="form_title">Паспортные данные</h3>
                            </div>
                      <div class="row">
                          <div class="form-group col-sm-4 ">
                              <label  class="col-form-label" for="passport_photo1">Фото лицевой стороны паспорта</label>
                              <div class="input-group ">
                                  <div class="custom-file">
                                      <input type="file" name="passport_photo1" class="form-control" id="passport_photo1" multiple="multiple">
                                  </div>
                              </div>
                              @error('passport_photo1')<p class="text-danger"> {{$message}}</p>@enderror
                          </div>
                          <div class="form-group col-sm-4 ">
                              <label  class="col-form-label" for="passport_photo2">Фото обратной стороны паспорта</label>
                              <div class="input-group ">
                                  <div class="custom-file">
                                      <input type="file" name="passport_photo2" class="form-control" id="passport_photo2" multiple="multiple">
                                  </div>
                              </div>
                              @error('passport_photo2')<p class="text-danger"> {{$message}}</p>@enderror
                          </div>
                      </div>
                        </div>
                    @endif


                @if($additional->employment)
                                <div class="form_block mb-4">
                                    <div class="form_head">
                                        <h3 class="form_title">Сведения о работе</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                                            <p class="field_label">Тип занятости *</p>
                                            <select class="custom-select form-control" name="employment_type" id="" >
                                                <option value="1">Работаю в организации</option>
                                                <option value="2">Собственный бизнес</option>
                                                <option value="3">Фрилансер(вместо название компании ссылка на публичный профиль)</option>
                                                <option value="4">Студент (вместо названия компании уч заведение)</option>
                                                <option value="5">Декрет, Пенсионер</option>
                                                <option value="6">Не работаю</option>

                                            </select>
                                            @error('employment_type')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                                            <p class="field_label">Наименование работодателя *</p>
                                            <input class="form-control" name="employer" id="" type="text" placeholder="Введите имя"
                                                   value="{{old('employer')}}">
                                            @error('employer')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                                            <p class="field_label">Должность *</p>
                                            <input class="form-control" name="position" id="" type="text" placeholder="Введите должность"
                                                   value="{{old('position')}}">
                                            @error('position')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                        <div class="col-12 col-md-6 col-lg-4 mb-4">
                                            <p class="field_label">Период работы *</p>
                                            <select class="custom-select form-control" name="employment_period" id="" >
                                                <option value="1">менее 3 месяцев</option>
                                                <option value="2">3 - 6 месяцев</option>
                                                <option value="3">6 - 12 месяцев</option>
                                                <option value="4">1 - 3 года</option>
                                                <option value="5">более 3 лет</option>
                                                <option value="6">не указано</option>

                                            </select>
                                            @error('employment_period')<p class="text-danger"> {{$message}}</p>@enderror
                                        </div>
                                    </div>
                                </div>
                @endif

                    @if($additional->files)
                        <div class="form_block mb-4">
                            <div class="form_head">
                                <h3 class="form_title">Фото доп. документа</h3>
                            </div>
                   <div class="row">
                       <div class="form-group col-sm-4 ">
                           <label  class="col-form-label" for="files_photo_front">Фото лицевой стороны</label>
                           <div class="input-group ">
                               <div class="custom-file">
                                   <input type="file" name="files_photo_front" class="form-control" id="files_photo_front" multiple="multiple">
                               </div>
                           </div>
                           @error('files_photo_front')<p class="text-danger"> {{$message}}</p>@enderror
                       </div>
                       <div class="form-group col-sm-4 ">
                           <label  class="col-form-label" for="files_photo_back">Фото оборотной стороны</label>
                           <div class="input-group ">
                               <div class="custom-file">
                                   <input type="file" name="files_photo_back" class="form-control" id="files_photo_back" multiple="multiple">
                               </div>
                           </div>
                           @error('files_photo_back')<p class="text-danger"> {{$message}}</p>@enderror
                       </div>
                   </div>
                        </div>
                    @endif

                    @if($additional->photo_with_passport)
                        <div class="form_block mb-4">
                            <div class="form_head">
                                <h3 class="form_title">Селфи с паспортом</h3>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 ">
                                    <label  class="col-form-label" for="photo_with_passport">Фото селфи с паспортом</label>
                                    <div class="input-group ">
                                        <div class="custom-file">
                                            <input type="file" name="photo_with_passport" class="form-control" id="photo_with_passport" multiple="multiple">
                                        </div>
                                    </div>
                                    @error('photo_with_passport')<p class="text-danger"> {{$message}}</p>@enderror
                                </div>

                            </div>
                        </div>
                    @endif


                    @if($additional->permanent_residency)
                        <div class="form_block mb-4">
                            <div class="form_head">
                                <h3 class="form_title">Разрешение на временное проживание</h3>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 ">
                                    <label  class="col-form-label" for="photo_rvp">Фото РВП</label>
                                    <div class="input-group ">
                                        <div class="custom-file">
                                            <input type="file" name="photo_rvp" class="form-control" id="photo_rvp" multiple="multiple">
                                        </div>
                                    </div>
                                    @error('photo_rvp')<p class="text-danger"> {{$message}}</p>@enderror
                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                    <p class="field_label">Номер РВП *</p>
                                    <input class="form-control" name="number_rvp" id="" type="text" placeholder="Введите Номер РВП"
                                           value="{{old('number_rvp')}}">
                                    @error('number_rvp')<p class="text-danger"> {{$message}}</p>@enderror
                                </div>

                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                    <p class="field_label">Дата выдачи РВП *</p>
                                    <input name="issue_date" class="datepicker" type="text" data-inputmask-alias="dd.mm.yyyy"  data-provide="datepicker" id="bdate" placeholder="dd.mm.yyyy">

                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                    <p class="field_label">Срок действия РВП *</p>
                                    <input name="term_date" class="datepicker" type="text" data-inputmask-alias="dd.mm.yyyy"  data-provide="datepicker"  placeholder="dd.mm.yyyy">

                                </div>
                                <div class="col-12 col-md-6 col-lg-4 mb-4">
                                    <p class="field_label">Орган выдачи РВП *</p>
                                    <input class="form-control" name="issuer" id="" type="text" placeholder="Введите текст"
                                           value="{{old('issuer')}}">
                                    @error('issuer')<p class="text-danger"> {{$message}}</p>@enderror
                                </div>

                            </div>
                        </div>
                    @endif

                    @if($additional->additional_phone)
                        <div class="form_block mb-4">
                            <div class="form_head">
                                <h3 class="form_title">Дополнительный номер</h3>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4 ">
                                    <div class="input-group ">
                                        <div class="custom-file">
                                            <input type="text" name="additional_phone" class="form-control"  >
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    @endif

                    <div class="form-row">
                    <button id="submit" name="sendData" type="submit" class="btn_submit">Отправить</button>
                    </div>


            </div>
            </div>
        </form>
    </div>


@endsection

@push('script')
    <script>
        $(document).ready(function () {

            const inputmask_options =
                {
                    mask: "99.99.9999",
                    alias: "date",
                    insertMode: false
                }

            $(".datepicker").datepicker().inputmask("99.99.9999", inputmask_options);


        });
    </script>
@endpush
