@extends('includes.main')

@section('title')
    {{ __('Register Page') }}
@endsection

@section('content')
    <section class="main w-100 overflow-hidden">
        <div class="container px-0">
            <div class="row">
                <div class="col-6 main-description text-white">
                    <img src="{{ asset('/public/images/home/logo.svg') }}" alt="">
                    <h1>Сервис аналитики продвижения в поисковых системах</h1>
                    <p>Полный контроль позиций проекта в Яндексе и Google.</p>
                    <a href="#" class="main-btn" data-toggle="modal" data-target="#exampleModal">Перейти к
                        аналитике</a>
                </div>
                <div class="col-6">
                    <img src="{{ asset('/public/images/home/home.png') }}" alt="" class="w-100 h-100">
                </div>
            </div>
        </div>
    </section>



    <section class="main-benefest">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_1.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Поисковые системы</h2>
                            <p>Съём в ПС Яндекс и Google</p>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_2.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Дешевле чем ТопВизор</h2>
                            <p>За счёт использования API MegaIndex</p>
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_3.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Составление отчётов</h2>
                            <p>Инструмент для создания отчётов </p>
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_4.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Сводка по проекту</h2>
                            <p>Отдельные группы в разные дни.</p>
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_5.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Съём частотности</h2>
                            <p>Инструмент для создания отчётов</p>
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_6.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Гостевые доступы</h2>
                            <p>Конкуренты в отдельных регионах </p>
                        </div>
                    </div>
                </div>


                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_7.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Региональность</h2>
                            <p>Для мультидоменных сайтов</p>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_8.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Конкуренты</h2>
                            <p>Конкуренты в отдельных регионах</p>
                        </div>
                    </div>
                </div>

                <div class="col-4">
                    <div class="main-benefest__collumn d-flex align-items-center">
                        <img src="{{ asset('/public/images/home/icon_9.svg') }}" alt="">
                        <div class="main-benefest__text">
                            <h2>Планы проверок</h2>
                            <p>Отдельные группы в разные дни.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Modal -->
@endsection

@section('modal-section')
    @include('includes.modal-auth')
@endsection
