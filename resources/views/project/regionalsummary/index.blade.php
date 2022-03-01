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

    <script type="text/javascript">
        const POSITION_DATES = [
            @foreach ($dates as $date)
                new Date('{{ \Carbon\Carbon::parse($date->action_date)->format('Y-m-d') }}'),
            @endforeach
        ];
    </script>

    <div class="content-wrapper container-fluid regional-summary">
        <div class="col-lg-12 mt-3">
            <div class="d-flex summary__charts--setting">
                <a href="#">
                    <img src="{{ asset('/public/images/project/summary/export.svg') }}" alt="">
                </a>
            </div>
        </div>
        <form method="get" class="regional-summary-filter mt-3 bg-white" action="{{ route('project.summary') }}">
            <div class="row">
                <div class="col-lg-4 form-inline">
                    <div class="d-flex w-100 justify-content-center">
                        <div class="regional-summary-filter__select-search">
                            <select id="filter_search_engine"
                                class="regional-summary-filter__search-select selectbox__small search-engine-filter--selectbox"
                                name="filter_search_engine">
                                <option value="yandex"
                                    {{ 'yandex' == $filter_search_engine || $filter_search_engine == '' ? 'selected' : '' }}>
                                    {{ __('Яндекс') }}</option>
                                <option value="google" {{ 'google' == $filter_search_engine ? 'selected' : '' }}>
                                    {{ __('Гугл') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 form-inline">
                    <div class="d-flex w-100 justify-content-center regional-summary-filter__select-group">
                        <select id="filter_search_engine"
                            class="regional-summary-filter__group-select selectbox__small search-engine-filter--selectbox"
                            name="filter_search_engine">
                            <option value="yandex"
                                {{ 'yandex' == $filter_search_engine || $filter_search_engine == '' ? 'selected' : '' }}>
                                {{ __('Все группы') }}</option>
                            <option value="google" {{ 'google' == $filter_search_engine ? 'selected' : '' }}>
                                {{ __('Google') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-inline">
                    <div class="d-flex w-100 justify-content-center">
                        <input type="text" class="w-100 datepicker" value="{{ $startTime->format('d.m.Y') }}"
                            name="filter_start_date">
                        <input type="text" class="w-100 datepicker" value="{{ $endTime->format('d.m.Y') }}"
                            name="filter_end_date">
                    </div>
                    {{-- <div class="input-group">
                        <input type="submit" class="btn btn-primary" value="{{ __('OK') }}" />
                    </div> --}}
                </div>
            </div>
        </form>

        <div class="row mt-5">
            <div class="col-lg-12">
                @if (count($regions) < 1)
                    <div class="alert alert-dark" role="alert">
                        {{ __('No Data') }}
                    </div>
                @else
                    <table class="regional-summary-table table">
                        <thead class="">
                            <tr>
                                <th scope="col">{{ __('Regions') }} <span>(а-я)</span></th>
                                <th scope="col">Кол-во <br />
                                    запросов</th>
                                <th scope="col">Ср. позиция</th>
                                <th scope="col">ТОП 3</th>
                                <th scope="col">ТОП 10</th>
                                <th scope="col">ТОП 30</th>
                                <th scope="col">ТОП 50</th>
                                <th scope="col">ТОП 100</th>
                            </tr>
                        </thead>
                        <tbody>


                            @if ($region_datas)
                                @foreach ($region_datas as $key => $region_data)


                                    @if ($region_data->statistics)
                                        <tr>
                                            {{-- Название региона --}}
                                            <td> @if ($regions[$region_data->region_id]) {{ $regions[$region_data->region_id] }} @endif</td>
                                            {{-- ------- --}}

                                            {{-- Количество запросов --}}
                                            <td class="inner_bottom">
                                                {{ $region_data->statistics->total }}
                                            </td>
                                            {{-- --------- --}}

                                            {{-- Средняя позиция --}}
                                            <td>
                                                <div class="inner_bottom">
                                                    <div
                                                        class="inner_bottom d-flex justify-content-center align-items-center">

                                                        {{ round($region_data->statistics->average) }}
                                                        <div>
                                                            @if ($region_data->statistics->total_change > 0)
                                                                <div class="select__project--table-rating-up">
                                                                    {{ round(abs($region_data->statistics->total_change)) }}
                                                                </div>
                                                            @else
                                                                <div class="select__project--table-rating-down">
                                                                    {{ round(abs($region_data->statistics->total_change)) }}
                                                                </div>
                                                            @endif

                                                        </div>

                                                    </div>
                                                </div>
                                            </td>
                                            {{-- --------- --}}


                                            {{-- ТОП 3 --}}
                                            <td>
                                                <div class="inner_bottom d-flex justify-content-center align-items-center">
                                                    {{ round($region_data->statistics->total_1_3) }}
                                                    <div>
                                                        @if ($region_data->statistics->total_1_3 - $region_data->statistics->total_first_1_3 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                @if (abs($region_data->statistics->total_1_3 - $region_data->statistics->total_first_1_3) != 0)
                                                                    {{ abs($region_data->statistics->total_1_3 - $region_data->statistics->total_first_1_3) }}
                                                                @endif
                                                            </div>
                                                        @elseif($region_data->statistics->total_1_3 - $region_data->statistics->total_first_1_3 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                @if (abs($region_data->statistics->total_1_3 - $region_data->statistics->total_first_1_3) != 0)
                                                                    {{ abs($region_data->statistics->total_1_3 - $region_data->statistics->total_first_1_3) }}
                                                                @endif
                                                            </div>
                                                        @else

                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            {{-- ------- --}}

                                            {{-- ТОП 10 --}}
                                            <td>
                                                <div class="inner_bottom d-flex justify-content-center align-items-center">
                                                    {{ round($region_data->statistics->total_1_10) }}
                                                    <div>
                                                        @if ($region_data->statistics->total_1_10 - $region_data->statistics->total_first_1_10 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                @if (abs($region_data->statistics->total_1_10 - $region_data->statistics->total_first_1_10) != 0)
                                                                    {{ abs($region_data->statistics->total_1_10 - $region_data->statistics->total_first_1_10) }}
                                                                @endif
                                                            </div>
                                                        @elseif($region_data->statistics->total_1_10 - $region_data->statistics->total_first_1_10 < 0) <div
                                                                class="select__project--table-rating-down">
                                                                @if (abs($region_data->statistics->total_1_10 - $region_data->statistics->total_first_1_10) != 0)
                                                                    {{ abs($region_data->statistics->total_1_10 - $region_data->statistics->total_first_1_10) }}
                                                                @endif
                                                            </div>
                                                        @else

                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            {{-- ------- --}}


                                            {{-- ТОП 30 --}}
                                            <td>
                                                <div class="inner_bottom d-flex justify-content-center align-items-center">
                                                    {{ round($region_data->statistics->total_11_30) }}
                                                    <div>
                                                        @if ($region_data->statistics->total_11_30 - $region_data->statistics->total_first_11_30 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                @if (abs($region_data->statistics->total_11_30 - $region_data->statistics->total_first_11_30) != 0)
                                                                    {{ abs($region_data->statistics->total_11_30 - $region_data->statistics->total_first_11_30) }}
                                                                @endif
                                                            </div>
                                                        @elseif($region_data->statistics->total_11_30 - $region_data->statistics->total_first_11_30 < 0) <div
                                                                class="select__project--table-rating-down">
                                                                @if (abs($region_data->statistics->total_11_30 - $region_data->statistics->total_first_11_30) != 0)
                                                                    {{ abs($region_data->statistics->total_11_30 - $region_data->statistics->total_first_11_30) }}
                                                                @endif
                                                            </div>
                                                        @else

                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            {{-- ------- --}}

                                            {{-- ТОП 50 --}}
                                            <td>
                                                <div class="inner_bottom d-flex justify-content-center align-items-center">
                                                    {{ round($region_data->statistics->total_31_50) }}
                                                    <div>
                                                        @if ($region_data->statistics->total_31_50 - $region_data->statistics->total_first_31_50 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                @if (abs($region_data->statistics->total_31_50 - $region_data->statistics->total_first_31_50) != 0)
                                                                    {{ abs($region_data->statistics->total_31_50 - $region_data->statistics->total_first_31_50) }}
                                                                @endif
                                                            </div>
                                                        @elseif($region_data->statistics->total_31_50 - $region_data->statistics->total_first_31_50 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                @if (abs($region_data->statistics->total_31_50 - $region_data->statistics->total_first_31_50) != 0)
                                                                    {{ abs($region_data->statistics->total_31_50 - $region_data->statistics->total_first_31_50) }}
                                                                @endif
                                                            </div>
                                                        @else

                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            {{-- ------- --}}


                                            {{-- ТОП 100 --}}
                                            <td>
                                                <div class="inner_bottom d-flex justify-content-center align-items-center">
                                                    {{ round($region_data->statistics->total_51_100) }}
                                                    <div>
                                                        @if ($region_data->statistics->total_51_100 - $region_data->statistics->total_first_51_100 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                @if (abs($region_data->statistics->total_51_100 - $region_data->statistics->total_first_51_100) != 0)
                                                                    {{ abs($region_data->statistics->total_51_100 - $region_data->statistics->total_first_51_100) }}
                                                                @endif
                                                            </div>
                                                        @elseif($region_data->statistics->total_51_100 - $region_data->statistics->total_first_51_100 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                @if (abs($region_data->statistics->total_51_100 - $region_data->statistics->total_first_51_100) != 0)
                                                                    {{ abs($region_data->statistics->total_51_100 - $region_data->statistics->total_first_51_100) }}
                                                                @endif
                                                            </div>
                                                        @else

                                                        @endif

                                                    </div>
                                                </div>
                                            </td>
                                            {{-- ------- --}}
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    </div>
@endsection

@section('modal-section')
    @include('project.region.modal-content')
@endsection
