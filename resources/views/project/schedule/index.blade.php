@extends('includes.main')

@section('title')
    {{ __('Schedule') }}
@endsection

@section('sidebar')
    @auth
        @include('includes.sidebar')
    @endauth
@endsection

@section('content')
    <div class="content-wrapper container-fluid shedule__bg">
        {{-- <div class="row mt-5">
            <div class="col-10">
                <h1>{{ __('Schedule') }}</h1>

            </div>
            <div class="col-2 text-right">
                <a class="btn btn-primary" title="{{ __('Create Project Plan') }}"
                    href="{{ route('project.schedule.create') }}">{{ __('Add') }}</a>
            </div>

        </div> --}}

        <div class="row">
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


            @if (count($schedules) < 1)
                <div class="alert alert-dark" role="alert">
                    {{ __('No Data') }}
                </div>
            @else
                <div class="col-lg-4">
                @foreach($schedules as $schedule)
                    @if($schedule->type=='weekly')

                            <div class="row summary__charts--plan-content mt-3 bg-white ">
                                <div class="col-lg-12">
                                    <div
                                            class="summary__charts--plan-content-title  d-flex justify-content-between align-items-start">
                                        <div>{{ $schedule->name }}</div>
                                        <div class="d-flex">
                                            <img src="{{ asset('/public/images/main-setting/edit.svg') }}" alt="">
                                            <img src="{{ asset('/public/images/main-setting/archive.svg') }}" alt=""
                                                 class="ml-2">
                                        </div>
                                    </div>
                                    <div class="summary__charts--plan-content-near">Ближайший съём через {{ abs(getClosestDate($dayOfWeek,explode(',',$schedule->date)) - $dayOfWeek   ) }} дня</div>

                                    <div class="summary__charts--plan-content-days d-flex justify-content-between mt-2">
                                        @for($i=1;$i<=7;$i++)
                                            @if (in_array($i, explode(',',$schedule->date)))
                                                {!! "<span class='current__day'>$weekdays[$i]</span>" !!}
                                            @else
                                                {{ $weekdays[$i] }}
                                                @endif
                                        @endfor
                                        <div class="summary__charts--plan-content-hour">
                                            в {{ $schedule->time }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                    @endif
                    @if($schedule->type=='everymonth')
                            <div class="row summary__charts--plan-content mt-3 bg-white ">
                                <div class="col-lg-12">
                                    <div
                                            class="summary__charts--plan-content-title d-flex justify-content-between align-items-start">

                                        <div> {{ $schedule->name }} </div>
                                        <div class="d-flex">
                                            <img src="{{ asset('/public/images/main-setting/edit.svg') }}" alt="">
                                            <img src="{{ asset('/public/images/main-setting/archive.svg') }}" alt=""
                                                 class="ml-2">
                                        </div>
                                    </div>
                                    <div class="summary__charts--plan-content-near">Ближайший съём через {{ abs(getClosestDate($dayOfMonth,explode(',',$schedule->date)) - $dayOfMonth   ) }}  дня</div>

                                    <div class="summary__charts--plan-content-days d-flex justify-content-between mt-2">
                                        @foreach(explode(',',$schedule->date) as $key=>$value)

                                            @if($key<count(explode(',',$schedule->date))-1)
                                                {{ $value }}
                                            @else
                                                {{ "и ".$value." числа" }}
                                            @endif
                                                @if($key<count(explode(',',$schedule->date))-2)
                                                    {{ ', ' }}
                                                @endif
                                        @endforeach

                                        <div class="summary__charts--plan-content-hour">
                                            в {{ $schedule->time }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                @endforeach
                </div>
{{--                <div class="col-lg-4">--}}
{{--                    <div class="row summary__charts--plan-content mt-3 bg-white ">--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <div--}}
{{--                                class="summary__charts--plan-content-title  d-flex justify-content-between align-items-start">--}}
{{--                                <div>Основной съём</div>--}}
{{--                                <div class="d-flex">--}}
{{--                                    <img src="{{ asset('/public/images/main-setting/edit.svg') }}" alt="">--}}
{{--                                    <img src="{{ asset('/public/images/main-setting/archive.svg') }}" alt=""--}}
{{--                                        class="ml-2">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="summary__charts--plan-content-near">Ближайший съём через 2 дня</div>--}}

{{--                            <div class="summary__charts--plan-content-days d-flex justify-content-between mt-2">--}}
{{--                                Пн--}}
{{--                                <span class="current__day">Вт</span>--}}
{{--                                Ср--}}
{{--                                <span class="current__day">Чт</span>--}}
{{--                                Пт--}}
{{--                                Сб--}}
{{--                                Вс--}}
{{--                                <div class="summary__charts--plan-content-hour">--}}
{{--                                    в 21:00--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="row summary__charts--plan-content mt-3 bg-white ">--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <div--}}
{{--                                class="summary__charts--plan-content-title d-flex justify-content-between align-items-start">--}}

{{--                                <div> Съём по аутсорсингу и <br />--}}
{{--                                    аутстаффингу</div>--}}
{{--                                <div class="d-flex">--}}
{{--                                    <img src="{{ asset('/public/images/main-setting/edit.svg') }}" alt="">--}}
{{--                                    <img src="{{ asset('/public/images/main-setting/archive.svg') }}" alt=""--}}
{{--                                        class="ml-2">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="summary__charts--plan-content-near">Ближайший съём через 2 дня</div>--}}

{{--                            <div class="summary__charts--plan-content-days d-flex justify-content-between mt-2">--}}
{{--                                5, 10, 15, 20, 25 и 30 числа--}}
{{--                                <div class="summary__charts--plan-content-hour">--}}
{{--                                    в 21:00--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="row summary__charts--plan-content mt-3 bg-white ">--}}
{{--                        <div class="col-lg-12">--}}
{{--                            <div--}}
{{--                                class="summary__charts--plan-content-title d-flex justify-content-between align-items-start">--}}
{{--                                <div> По праздникаму </div>--}}
{{--                                <div class="d-flex">--}}
{{--                                    <img src="{{ asset('/public/images/main-setting/edit.svg') }}" alt="">--}}
{{--                                    <img src="{{ asset('/public/images/main-setting/archive.svg') }}" alt=""--}}
{{--                                        class="ml-2">--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="summary__charts--plan-content-near">В архиве</div>--}}

{{--                            <div class="summary__charts--plan-content-days d-flex justify-content-between mt-2">--}}
{{--                                5, 10, 15, 20, 25 и 30 числа--}}
{{--                                <div class="summary__charts--plan-content-hour">--}}
{{--                                    в 21:00--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
                {{-- <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Планы</th>
                            <th scope="col">Type</th>
                            <th scope="col">Dates</th>
                            <th scope="col">Time</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($tasks as $item)

                            @if ($item->schedules->isEmpty())

                            @else
                                <tr>
                                    <td scope="row">
                                        {{ $item->name }}
                                    </td>
                                    <td>{{ $item->schedules->first()->type }} </td>
                                    <td>
                                        {{ $item->schedules->first()->date }}

                                    </td>
                                    <td>

                                        {{ $item->schedules->first()->time }}

                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('project.schedule.edit', [$item->id]) }}"
                                            title="Редактировать план"><i class="fas fa-edit"></i></a>

                                        <a href="javascript:void(0)" title="{{ __('Delete') }}"
                                            data-project-competitor-id="{{ $item->id }}"
                                            class="destroy-project-schedule--link"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table> --}}
            @endif
        </div>
    </div>
    </div>
@endsection

@section('modal-section')
    @include('project.region.modal-content')
@endsection
