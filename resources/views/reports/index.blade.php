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
    <div class="content-wrapper container-fluid reports">

        <div class="col-lg-12 my-3">
            <div class="d-flex summary__charts--setting">
                <a href="#">
                    <img src="{{ asset('/public/images/setting_navigator/add.svg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('/public/images/setting_navigator/save.svg') }}" alt="">
                </a>
                <a href="#">
                    <img src="{{ asset('/public/images/setting_navigator/link.svg') }}" alt="">
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
                <div class="col-lg-8">
                    {{-- Блок заголовок Отчетов --}}
                    <div class="reports__title bg-white p-15">
                        <h2>Отчёт за Декабрь 2021</h2>
                        <p>Добрый день! Подготовили отчёт по позициям за декабрь 2021. Просим ознакомиться.</p>
                    </div>
                    {{-- ------- --}}

                    {{-- Средняя позиция --}}
                    <div class="reports__line-charts bg-white p-15 mt-3">
                        <div class="d-flex justify-content-between">
                            <div class="reports__line-charts--title">
                                <h2>Средняя позиция за год</h2>
                                <p>Яндекс, Санкт-Перебург, ср. позиция, 1 год</p>
                            </div>
                            <div class="reports__line-charts--setting">
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/edit.svg') }}" alt="">
                                </a>
                                <a href="">
                                    <img src="{{ asset('/public/images/setting_card/delete.svg') }}" alt="">
                                </a>
                            </div>
                        </div>
                        {!! $reportCharts->container() !!}
                        <div class="reports__line-charts--comment">
                            <div class="reports__line-charts--comment-title">
                                Комментарий:
                            </div>
                            <p>Несмотря на временную просадку позиций, нам удалось вернуть предыдущие значения и даже
                                повысить
                                их. Предпосылок для последующего снижения не предвидется.</p>
                        </div>
                    </div>
                    {{-- ------ --}}

                    {{-- График диаграмма --}}
                    <div class="reports__pie-charts bg-white p-15 mt-3">
                        <div class="d-flex justify-content-between">
                            <div class="reports__pie-charts--title">
                                <h2>Нижний Новгород</h2>
                            </div>
                            <div class="reports__line-charts--setting">
                                <a href="#">
                                    <img src="{{ asset('/public/images/setting_card/edit.svg') }}" alt="">
                                </a>
                                <a href="">
                                    <img src="{{ asset('/public/images/setting_card/delete.svg') }}" alt="">
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                Яндекс, Нижний Новгород
                                {!! $reportPieCharts->container() !!}

                            </div>
                            <div class="col-lg-6">
                                <div class="reports__line-charts--comment">
                                    <div class="reports__line-charts--comment-title">
                                        Комментарий:
                                    </div>
                                    <p>Несмотря на временную просадку позиций, нам удалось вернуть предыдущие значения и
                                        даже
                                        повысить
                                        их. Предпосылок для последующего снижения не предвидется.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ------ --}}

                    {{-- Топы --}}
                    <div class="reports__best bg-white p-15 mt-3">
                        <div class="d-flex justify-content-between">
                            <div class="reports__pie-charts--title">
                                <h2>Позиции по ИТ Аутсорсингу</h2>
                                <p>Яндекс, Москва, 14.11.2021 - 14.10.2021, ИТ Аутсорсинг</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-7">
                                <div class="reports__best--title d-flex justify-content-between align-items-center">
                                    <div>Запросы (234)</div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="reports__best--title-procent">
                                            41%
                                        </div>
                                        <div class="reports__best--title-date"> 14.09.21</div>
                                    </div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="reports__best--title-procent">
                                            41%
                                        </div>
                                        <div class="reports__best--title-date"> 14.09.21</div>
                                    </div>
                                </div>
                                <?php for($i = 1; $i <= 10; $i++){ ?>
                                <div class="reports__best--content d-flex justify-content-between align-items-center">
                                    <div>it аутсорсинг</div>
                                    <div class="d-flex align-items-center justify-content-start">4 <div
                                            class="select__project--table-rating-up">2
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-start">1 <div
                                            class="select__project--table-rating-down">2</div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="col-lg-5">
                                <div class="reports__line-charts--comment">
                                    <div class="reports__line-charts--comment-title">
                                        Комментарий:
                                    </div>
                                    <p>Несмотря на временную просадку позиций, нам удалось вернуть предыдущие значения и
                                        даже
                                        повысить
                                        их. Предпосылок для последующего снижения не предвидется.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ------ --}}

                    {{-- Сводка по регионам --}}
                    <div class="reports__region bg-white p-15 mt-3">
                        <div class="d-flex justify-content-between">
                            <div class="reports__pie-charts--title">
                                <h2>Сводка по регионам</h2>
                                <p>Сводка по регионам на 14.11.2021</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="reports__best--title d-flex justify-content-between align-items-center">
                                    <div>Регионы</div>
                                    <div>Ср. позиция</div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="reports__best--title-procent">
                                            41%
                                        </div>
                                        <div class="reports__best--title-date"> Топ-3</div>
                                    </div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="reports__best--title-procent">
                                            41%
                                        </div>
                                        <div class="reports__best--title-date"> Топ-10</div>
                                    </div>
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="reports__best--title-procent">
                                            41%
                                        </div>
                                        <div class="reports__best--title-date"> Топ-30</div>
                                    </div>
                                </div>
                                <?php for($i = 1; $i <= 10; $i++){ ?>
                                <div class="reports__region--content d-flex justify-content-between align-items-center">
                                    <div>Москва</div>
                                    <div class="d-flex align-items-center justify-content-center">30 <div
                                            class="select__project--table-rating-up">20
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">1 <div
                                            class="select__project--table-rating-down">2</div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">1 <div
                                            class="select__project--table-rating-down">2</div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center">1 <div
                                            class="select__project--table-rating-down">2</div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <div class="reports__line-charts--comment">
                                    <div class="reports__line-charts--comment-title">
                                        Комментарий:
                                    </div>
                                    <p>Несмотря на временную просадку позиций, нам удалось вернуть предыдущие значения и
                                        даже
                                        повысить
                                        их. Предпосылок для последующего снижения не предвидется.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- ------ --}}

                </div>
                <div class="col-lg-4">
                    {{-- Дата отчёта --}}
                    <div class="reports__title bg-white p-15 text-center">
                        <p>Дата отчёта</p>
                        <div>01.01.2021</div>
                    </div>
                    {{-- ------- --}}

                    {{-- Содержание отчёта --}}
                    <div class="reports__list bg-white mt-3">
                        <p class="text-center">Содержание отчёта</p>

                        <div class="reports__list--single d-flex justify-content-between">
                            <div>Отчёт за Декабрь 2021</div>
                            <div class="reports__list--view">Текст</div>
                        </div>

                        <div class="reports__list--single d-flex justify-content-between">
                            <div>Нижний Новгород</div>
                            <div class="reports__list--view">Диаграмма</div>
                        </div>
                    </div>
                    {{-- ------- --}}

                    {{-- Последние отчеты --}}
                    <div class="reports__list bg-white mt-3">
                        <p class="text-center">Последние отчеты</p>

                        <div class="reports__list--single d-flex justify-content-between">
                            <div>Отчёт за декабрь 2021</div>

                        </div>

                        <div class="reports__list--single d-flex justify-content-between">
                            <div>Отчёт за декабрь 2021</div>
                        </div>
                    </div>
                    {{-- ------- --}}

                    {{-- Кнопки --}}
                    <button type="submit" class="main-btn w-100 mt-4    ">
                        Добавить блок
                    </button>
                    <button type="submit" class="success-btn w-100 mt-4    ">
                        Составить отчёт
                    </button>
                    {{-- ------- --}}

                </div>
            @endif
        </div>
    </div>

    <script src="{{ $reportCharts->cdn() }}"></script>
    {{ $reportCharts->script() }}
    {{ $reportPieCharts->script() }}
@endsection
