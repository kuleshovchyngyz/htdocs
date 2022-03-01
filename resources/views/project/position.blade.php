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

    <input type="hidden" id="project-id" value="{{ $project->id }}">
    <script type="text/javascript">
        const POSITION_DATES = [
            @foreach ($dates as $date)
                new Date('{{ \Carbon\Carbon::parse($date->action_date)->format('Y-m-d') }}'),
            @endforeach
        ];
    </script>

    <div class="content-wrapper container-fluid">
        <form class="filter-form">
            @if (!auth()->user()->hasRole('client'))
                <div class="mt-3">
                    <div class="query-group-index__icons">

                        <a href="#" class="refresh-position--button__popup">
                            <img src="{{ asset('/public/images/setting_navigator/return.svg') }}" alt="">
                        </a>

                        <a href="#">
                            <img src="{{ asset('/public/images/setting_navigator/time.svg') }}" alt="">
                        </a>

                        <a href="#">
                            <img src="{{ asset('/public/images/setting_navigator/import.svg') }}" alt="">
                        </a>
                    </div>
                </div>
            @endif
            <input type="hidden" name="filter_init" id="filter_init" value="main">
            <input type="hidden" name="sort_date" value="{{ $position_obj->sort_date }}" id="sort_date">
            <input type="hidden" name="sort_type" value="{{ $position_obj->sort_type }}" id="sort_type">
            <input type="hidden" name="competitor" value="{{ $position_obj->filter_competitor }}" id="competitor">

            <div class=" row mt-3">

                {{-- <div class="input-group">
                        <button type="button" class="btn btn-primary refresh-position--button__popup"><i
                                class="fas fa-sync"></i></button>
                    </div> --}}

                <div class="col-lg-3 input-group">
                    <select id="filter_search_engine" class="form-control search-engine-filter--selectbox"
                        name="filter_search_engine">
                        <option value="yandex"
                            {{ 'yandex' == $filters['values']['search_engine'] || $filters['values']['search_engine'] == ''? 'selected': '' }}>
                            {{ __('Яндекс') }}</option>
                        <option value="google" {{ 'google' == $filters['values']['search_engine'] ? 'selected' : '' }}>
                            {{ __('Гугл     ') }}</option>
                    </select>
                </div>
                @if (count($filters['regions']) > 0)
                    <div class="col-lg-3 input-group">
                        <select id="filter_region_id" class="form-control" name="filter_region_id">
                            @foreach ($filters['regions'] as $filterRegion)
                                <option value="{{ $filterRegion->id }}"
                                    {{ $filterRegion->id == $filters['values']['region_id'] ? 'selected' : '' }}>
                                    {{ $filterRegion->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-lg-3 input-group">
                    <select id="filter_query_group_id" class="form-control" name="filter_query_group_id">
                        <option value="">{{ __('All Groups') }}</option>
                        @foreach ($filters['query_groups'] as $queryGroup)
                            <option value="{{ $queryGroup['id'] }}"
                                {{ $queryGroup['id'] == $filters['values']['query_group_id'] ? 'selected' : '' }}>
                                @php echo str_repeat('&nbsp;', $queryGroup['level']); @endphp {{ $queryGroup['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 input-group">
                    <input type="text" class="form-control datepicker" value="{{ $filters['values']['start_date'] }}"
                        name="filter_start_date">
                    <input type="text" class="form-control datepicker" value="{{ $filters['values']['end_date'] }}"
                        name="filter_end_date">
                </div>

                <div class="col-lg-12 input-group">
                    <input id="filter_ok_btn" type="submit" class="btn btn-primary" value="{{ __('OK') }}" />
                </div>

            </div>
            <div class="row ml-1">
                <div id="competitors" class="hidden ml-3" style="display: block;">

                    @if ($position_obj->filter_competitor == 'self')@php($active='active')@else @php($active='') @endif
                    <a href="#" data-id="main" id="project_domain_id"
                        class="btn btn-secondary domain  {{ $active }}">{{ $project->projectsubdomain($region_id) }}</a>
                    @foreach ($competitors as $competitor)
                        @if ($competitor->hasregion($region_id))
                            @if ($position_obj->filter_competitor == $competitor->name)@php($active='active')@else @php($active='') @endif
                            <a href="#" data-id="{{ $competitor->id }}"
                                class="btn btn-secondary domain {{ $active }}"
                                title="{{ $competitor->name }} ({{ $competitor->url }})">{{ $competitor->name }}</a>
                        @endif
                    @endforeach
                    <a href="#" data-id="compare" class="btn btn-secondary domain" data-id="-1"> <i
                            class="fas fa-flag-checkered"></i></a>
                </div>
            </div>
        </form>

        @if ($statistics)
            <div class="summary mt-2 d-flex">
                <div class="subsummary d-flex">
                    <a href="#" class="outer">
                        <div class="inner_top">
                            {{-- <i class="d icon icon-arrow-up-circle"></i> --}}
                            {{-- <i class="fas fa-arrow-up-circle"></i> --}}
                            <i class="fas fa-arrow-circle-up green"></i>
                            {{ $statistics->increase_perecent }}%
                        </div>
                        <div class="inner_bottom">
                            {{ $statistics->increase }}

                        </div>
                    </a>
                    <a href="#" class="outer">
                        <div class="inner_top">

                            <span class="fa-stack">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fas fa-equals fa-stack-1x fa-inverse"></i>
                            </span>
                            {{ $statistics->unchanged_perecent }}%
                        </div>
                        <div class="inner_bottom">
                            {{ $statistics->unchanged }}
                        </div>
                    </a>
                    <a href="#" class="outer">
                        <div class="inner_top">
                            <i class="fas fa-arrow-circle-down red"></i>
                            {{ $statistics->decrease_perecent }}%
                        </div>
                        <div class="inner_bottom">
                            {{ $statistics->decrease }}
                        </div>
                    </a>
                </div>
                <div class="subsummaryone d-flex">
                    <div class="outer">
                        <div class="inner_top">
                            Средняя
                        </div>
                        <div class="inner_bottom">
                            <div class="inner_bottom d-flex">

                                {{ round($statistics->average) }}
                                <div class="beside_low ">
                                    @if ($statistics->total_change > 0)
                                        <i class="fas fa-arrow-circle-up green"></i>
                                    @else
                                        <i class="fas fa-arrow-circle-down red"></i>
                                    @endif
                                    {{ round(abs($statistics->total_change)) }}
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <div class="subsummary d-flex">
                    <div class="outer">
                        <div class="inner_top">
                            1-3({{ $statistics->total_1_3_percent }}%)
                        </div>
                        <div class="inner_bottom">
                            <div class="inner_bottom d-flex">

                                {{ round($statistics->total_1_3) }}
                                <div class="beside_low ">
                                    @if ($statistics->total_1_3 - $statistics->total_first_1_3 > 0)
                                        <i class="fas fa-arrow-circle-up green"></i>
                                    @elseif($statistics->total_1_3 - $statistics->total_first_1_3 < 0) <i class="fas fa-arrow-circle-down red"></i>
                                    @else

                                    @endif
                                    @if (abs($statistics->total_1_3 - $statistics->total_first_1_3) != 0)
                                        {{ abs($statistics->total_1_3 - $statistics->total_first_1_3) }}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="outer">
                        <div class="inner_top">
                            1-10({{ $statistics->total_1_10_percent }}%)
                        </div>
                        <div class="inner_bottom">
                            <div class="inner_bottom d-flex">

                                {{ round($statistics->total_1_10) }}
                                <div class="beside_low ">
                                    @if ($statistics->total_1_10 - $statistics->total_first_1_10 > 0)
                                        <i class="fas fa-arrow-circle-up green"></i>
                                    @elseif($statistics->total_1_10 - $statistics->total_first_1_10 < 0) <i class="fas fa-arrow-circle-down red"></i>
                                    @else

                                    @endif
                                    @if (abs($statistics->total_1_10 - $statistics->total_first_1_10) != 0)
                                        {{ abs($statistics->total_1_10 - $statistics->total_first_1_10) }}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="outer">
                        <div class="inner_top">
                            11-30({{ $statistics->total_11_30_percent }}%)
                        </div>
                        <div class="inner_bottom">
                            <div class="inner_bottom d-flex">

                                {{ round($statistics->total_11_30) }}
                                <div class="beside_low ">
                                    @if ($statistics->total_11_30 - $statistics->total_first_11_30 > 0)
                                        <i class="fas fa-arrow-circle-up green"></i>
                                    @elseif($statistics->total_11_30 - $statistics->total_first_11_30 < 0) <i class="fas fa-arrow-circle-down red"></i>
                                    @else

                                    @endif
                                    @if (abs($statistics->total_11_30 - $statistics->total_first_11_30) != 0)
                                        {{ abs($statistics->total_11_30 - $statistics->total_first_11_30) }}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="outer">
                        <div class="inner_top">
                            31-50({{ $statistics->total_31_50_percent }}%)
                        </div>
                        <div class="inner_bottom">
                            <div class="inner_bottom d-flex">

                                {{ round($statistics->total_31_50) }}
                                <div class="beside_low ">
                                    @if ($statistics->total_31_50 - $statistics->total_first_31_50 > 0)
                                        <i class="fas fa-arrow-circle-up green"></i>
                                    @elseif($statistics->total_31_50 - $statistics->total_first_31_50 < 0) <i class="fas fa-arrow-circle-down red"></i>
                                    @else

                                    @endif
                                    @if (abs($statistics->total_31_50 - $statistics->total_first_31_50) != 0)
                                        {{ abs($statistics->total_31_50 - $statistics->total_first_31_50) }}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="outer">
                        <div class="inner_top">
                            51-100({{ $statistics->total_51_100_percent }}%)
                        </div>
                        <div class="inner_bottom">
                            <div class="inner_bottom d-flex">

                                {{ round($statistics->total_51_100) }}
                                <div class="beside_low ">
                                    @if ($statistics->total_51_100 - $statistics->total_first_51_100 > 0)
                                        <i class="fas fa-arrow-circle-up green"></i>
                                    @elseif($statistics->total_51_100 - $statistics->total_first_51_100 < 0) <i class="fas fa-arrow-circle-down red"></i>
                                    @else

                                    @endif
                                    @if (abs($statistics->total_51_100 - $statistics->total_first_51_100) != 0)
                                        {{ abs($statistics->total_51_100 - $statistics->total_first_51_100) }}
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        @endif

        <div class="row mt-1">
            <div class="col-lg-8">
                <h3><span class="loading-icon-container d-none"><i class="fas fa-sync"></i></span></h3>
            </div>
        </div>

        <div class="col-lg-12 position-table-container mt-4">

            @if (count($positions) < 1)
                <div class="alert alert-dark" role="alert">
                    {{ __('No Positions') }}
                </div>
            @else
                @include('project.competitor.position', $c)
                <table class="table  table-sm positions_table" id="positions_table_main">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{ __('Queries') }} ({{ $queries->count() }})</th>
                            @foreach ($dates as $date)
                                <th scope="col"><a href="#" data-clicked="0"
                                        data-sort="{{ \Carbon\Carbon::parse($date->action_date)->format('Y-m-d') }}"
                                        class="sort_date" @if (\Carbon\Carbon::parse($date->action_date)->format('Y-m-d') == $sort_date)
                                        style="color: {{ $sort_color }}"
                            @endif
                            >{{ \Carbon\Carbon::parse($date->action_date)->format('d.m.Y') }}</a></th>
            @endforeach
            </tr>
            </thead>
            <tbody>
                @foreach ($queries as $query)
                    <tr data-query-id="{{ $query->query_id }}" class="">
                        <td>{{ $query->query_name }} </td>

                        @foreach ($dates as $date)
                            @php($positionKey = $query->query_id . '--' . $date->action_date)

                            <td>

                                @if (isset($positions[$positionKey]))
                                    @if (isset($positions[$positionKey]['yandex']))
                                        @foreach ($positions[$positionKey]['yandex'] as $yandex)
                                            <div class="yandex-position-container {{ $yandex->position_class_name }}">

                                                @if ($yandex->position > 0 && $yandex->position < 100)
                                                    {{ $yandex->position }}
                                                    <i class="tiny {{ change($yandex->change) }}">
                                                        <i class="fas {{ arrow_change($yandex->change) }}"></i>
                                                        {{ abs($yandex->change) }}
                                                    </i>
                                                    <i class="fa {{ $yandex->matched_icon }} text-secondary float-right"
                                                        data-toggle="popover" title={{ $yandex->target_path }}
                                                        aria-hidden="true" data-content={{ $yandex->full_url }}></i>
                                                @else
                                                    {{ '--' }}
                                                @endif
                                            </div>
                                        @endforeach

                                    @endif
                                    @if (isset($positions[$positionKey]['google']))
                                        @foreach ($positions[$positionKey]['google'] as $google)
                                            <div class="google-position-container {{ $google->position_class_name }}">
                                                @if ($google->position > 0 && $google->position < 100)
                                                    {{ $google->position }}
                                                    <i class="tiny {{ change($google->change) }}">
                                                        <i class="fas {{ arrow_change($google->change) }}"></i>
                                                        {{ abs($google->change) }}
                                                    </i>

                                                    <i class="fa {{ $google->matched_icon }} text-secondary float-right"
                                                        data-toggle="popover" title={{ $google->target_path }}
                                                        aria-hidden="true" data-content={{ $google->full_url }}></i>
                                                @else
                                                    {{ '--' }}
                                                @endif
                                            </div>

                                        @endforeach
                                    @endif
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            </table>
            @endif
        </div>
    </div>
    </div>
@endsection

@section('modal-section')
    @include('position.modal-content')
@endsection
