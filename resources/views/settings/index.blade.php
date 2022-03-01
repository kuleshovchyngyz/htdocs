@extends('includes.main')

@section('title')
    {{ __('Project Position') }}
@endsection

@section('sidebar')
    @auth
        @include('includes.sidebar')
    @endauth
@endsection


@section('content')
    <div class="content-wrapper container-fluid setting-bg">
        <div class="col-lg-12 my-3">
            <div class="d-flex summary__charts--setting">
                <a href="#">
                    <img src="{{ asset('/public/images/setting_navigator/add.svg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('/public/images/setting_navigator/export.svg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('/public/images/setting_navigator/import.svg') }}" alt="">
                </a>
            </div>
        </div>
        <div class="row">
            @if (0)
                <div class="alert alert-dark" role="alert">
                    {{ __('No Data') }}
                </div>
            @else
                <div class="col-lg-6">
                    <div class="project-region-block bg-white">
                        <div class="project-region__title-setting d-flex justify-content-between">
                            <div class="project-region-title">
                                <div>Доступы</div>
                                <span>Выбрано: 3</span>
                            </div>
                            <div class="project-region-setting">
                                <a href="#"><img src="{{ asset('/public/images/setting_card/search.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/plus.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/edit.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/archive.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/delete.svg') }}" alt=""></a>
                            </div>
                        </div>


                        <div class="project-region__content d-flex justify-content-between ">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="project-region__content-checkbox">
                                        <input type="checkbox" name="ids[]" value="18" data-active="1"
                                            data-name="BitRaid.ru" id="select__project--checkbox-checked-18">
                                        <label for="select__project--checkbox-checked-18"></label>
                                    </div>
                                    <div class="setting__content-title ml-3">
                                        <div> Клиентский доступ</div>
                                        <span> Просмотр, активен до 14.11.21</span>
                                    </div>

                                </div>
                            </div>
                            <div class="setting--set">
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/eye.svg') }}" alt="">
                                </a>
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/copy.svg') }}" alt="">
                                </a>
                            </div>
                        </div>

                        <div class="project-region__content d-flex justify-content-between ">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="project-region__content-checkbox">
                                        <input type="checkbox" name="ids[]" value="18" data-active="1"
                                            data-name="BitRaid.ru" id="select__project--checkbox-checked-18">
                                        <label for="select__project--checkbox-checked-18"></label>
                                    </div>
                                    <div class="setting__content-title ml-3">
                                        <div> Клиентский доступ</div>
                                        <span> Просмотр, активен до 14.11.21</span>
                                    </div>

                                </div>
                            </div>
                            <div class="setting--set">
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/eye.svg') }}" alt="">
                                </a>
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/copy.svg') }}" alt="">
                                </a>
                            </div>
                        </div>


                        <div class="project-region__content d-flex justify-content-between ">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="project-region__content-checkbox">
                                        <input type="checkbox" name="ids[]" value="18" data-active="1"
                                            data-name="BitRaid.ru" id="select__project--checkbox-checked-18">
                                        <label for="select__project--checkbox-checked-18"></label>
                                    </div>
                                    <div class="setting__content-title ml-3">
                                        <div> Клиентский доступ</div>
                                        <span> Просмотр, активен до 14.11.21</span>
                                    </div>

                                </div>
                            </div>
                            <div class="setting--set">
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/eye.svg') }}" alt="">
                                </a>
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/copy.svg') }}" alt="">
                                </a>
                            </div>
                        </div>

                        <div class="project-region__content d-flex justify-content-between ">
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="project-region__content-checkbox">
                                        <input type="checkbox" name="ids[]" value="18" data-active="1"
                                            data-name="BitRaid.ru" id="select__project--checkbox-checked-18">
                                        <label for="select__project--checkbox-checked-18"></label>
                                    </div>
                                    <div class="setting__content-title ml-3">
                                        <div> Клиентский доступ</div>
                                        <span> Просмотр, активен до 14.11.21</span>
                                    </div>

                                </div>
                            </div>
                            <div class="setting--set">
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/eye.svg') }}" alt="">
                                </a>
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/copy.svg') }}" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <form method="POST" action="" class="bg-white">
                        @csrf
                        @method('PUT')
                        <div class="modal-form__content">
                            <div class="form-group row">
                                <div
                                    class="form__project-name from__icon w-100 position-relative d-flex align-items-center">
                                    <input id="name" type="text" class="form-control form__user edit__name" name="name"
                                        value="" required autofocus placeholder="Название проекта">

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="form__site from__icon w-100 position-relative  d-flex align-items-center">

                                    <input id="url" type="text"
                                        class="form-control edit__url @error('url') is-invalid @enderror" name="url"
                                        required placeholder="Сайт" value="">

                                    @error('url')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="w-100">
                                    <button type="submit" class="main-btn w-100 mt-4    ">
                                        {{ __('Сохранить') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
