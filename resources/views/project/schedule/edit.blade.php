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
    <?php $region_to_filter = $f["region_to_filter"] ;
    $filters = $f["filters"] ;
    $group_regions = $f["group_regions"];
     $arr = array() ;
     $arr1 = array() ;
    //dd($task->ready_scheduled_position->get_region());
    ?>
    <input type="hidden" id="project-id" value="{{ $task->project_id }}">
    <input type="hidden" id="task-id" value="{{ $task->id }}">


    <input type="hidden" id="Selected_dates" value="{{ $task->schedules->first()->date }}">
    <input type="hidden" id="editpage_load" value="{{$task->schedules->first()->type}}">
    <input type="hidden" id="dates" value="">
    <input type="hidden" id="type" value="">
    <input type="hidden" name="uuid" id="uuid" value="{{  uniqid() }}">
    <div class="content-wrapper container-fluid">
        <div class="row mt-5">
            <div class="col-lg-8 offset-md-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-between"><h1>{{ __('Create Plan') }}</h1>


                    </div>
                    {{--                    <form method="POST"  action="{{ route('project.schedule') }}">--}}
                    <div class="card-body ml-5">
                        <div class="form-group row card likeinput1">


                            @csrf
                            <div class="">
                                <div class="dropdown ml-1">
                                    <button class="btn btn-custom dropdown-toggle likeinput" type="button" id="dropdownMenuButtonSchedule" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @if($task->schedules->first()->type == "weekly")
                                            {{ 'По дням недели' }}

                                        @else
                                            {{ 'По дням месяца' }}
                                        @endif
                                    </button>
                                    <div class="dropdown-menu likeinput" aria-labelledby="dropdownMenuButtonSchedule">
                                        <a class="dropdown-item " href="#">По дням недели</a>
                                        <a class="dropdown-item" href="#">По дням месяца</a>
                                    </div>
                                </div>
                                <div class="ml-1 mt-1">
                                    @if($task->schedules->first()->type =='weekly')
                                        <input type="hidden" id="myDay" value="{{ $task->schedules->first()->date }}">
                                        <input id="myTime" value="{{ $task->schedules->first()->time }}" class="flatpickr flatpickr-input active" type="text" placeholder="Выберите время..." data-id="timePicker" readonly="readonly">
                                        <input id="myDate"  class="flatpickr flatpickr-input ml-2 active" type="hidden" placeholder="Select Date.." data-id="timePicker" readonly="readonly" >
                                    @else
                                        <input type="hidden" id="myDay" value="">
                                        <input id="myTime" value="{{ $task->schedules->first()->time }}" class="flatpickr flatpickr-input active" type="hidden" placeholder="Выберите время..." data-id="timePicker" readonly="readonly">
                                        <input id="myDate" value="{{ $task->schedules->first()->date }}" class="flatpickr flatpickr-input ml-2 active" type="text" placeholder="Select Date.." data-id="timePicker" readonly="readonly" >
                                    @endif
                                </div>


                            </div>
                            <div class="form-group row weekdays">

                                <div class="days ml-5">

                                    <i data-day="1" class="">Пн</i>
                                    <i data-day="2" class="">Вт</i>
                                    <i data-day="3" class="">Ср</i>
                                    <i data-day="4" class="">Чт</i>
                                    <i data-day="5" class="">Пт</i>
                                    <i data-day="6" class="">Сб</i>
                                    <i data-day="0" class="">Вс</i>
                                </div>

                            </div>


                        </div>
                        <div class="form-group row d-flex justify-content-between w-50 align-items-center">
                            <span class="ml-3">Название плана</span><input class="mr-5 w-50" type="text" id="plan_name" name="plan_name" value="{{ $task->name }}">
                        </div>
                        <div class="form-group row text-left">
                            <div class="col-md-10">
                                <button type="submit" id = "get_position_button" class="btn btn-success">
                                    {{ __('Capture Positions') }}
                                </button>

                                <a class="btn btn-secondary" href="{{ url()->previous() }}">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    {{--                    </form>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="modal-content__hidden modal-content--append-region">
        <div class="modal-title">
            {{__('Add Region')}}
        </div>
        <div class="modal-content">
            <div class="form-group">
                <label>{{__('Region') }}</label>
                <select class="custom-select" name="region_id">
                    <option value="0">{{ __('No Region') }}</option>
                    @foreach ($region_to_filter as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="add_region_button" class="btn btn-primary mk">{{ __('OK') }}</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
        </div>
    </div>
    <div class="modal-content__hidden modal-content--refresh-popup-container1">
        <div class="modal-title">
            {{__('Refresh Position')}}
        </div>
        <div class="modal-content">
            <div class="search-setup-container">

                <label for="filter_query_group_id">{{ __('Query Groups') }}: </label>
                <ul class="list-group query-group-setup list-group-sm">
                    <li class="list-group-item bg-info list-group-item-main">
                        <div class="d-flex w-100 justify-content-between">
                            <span>{{ __('All Query Groups') }}</span>
                            <div class="custom-control custom-switch yandex-switch">
                                <input type="checkbox" name="all-query-group" checked="checked" id="all-query-group">
                            </div>
                        </div>
                    </li>
                    @foreach ($filters['query_groups'] as $queryGroup)
                        <li class="list-group-item query-group--list">
                            <div class="d-flex w-100 justify-content-between">
                                <span>@php echo str_repeat('&nbsp;', $queryGroup['level']); @endphp {{ $queryGroup['name'] }}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="[query_group][{{ $queryGroup['id'] }}]" data-query-group-id="{{ $queryGroup['id'] }}" checked="checked">
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <hr/>
                <label for="filter_query_group_id">{{ __('Search Engines') }}: </label>
                <ul class="list-group yandex-setup list-group-sm" data-search-list="yandex">
                    <li class="list-group-item bg-info list-group-item-main">
                        <div class="d-flex w-100 justify-content-between">
                            <span>{{ __('Yandex') }}</span>
                            <div class="custom-control custom-switch yandex-switch">
                                <button type="button" class="btn btn-sm btn-primary add-region--button1">{{ __('+ Region') }}</button>
                                <input type="checkbox" name="yandex-search" class="yandex-search" checked="checked">
                            </div>
                        </div>
                    </li>
                    @foreach ($group_regions as $filterRegion)
                        <li class="list-group-item region--list">
                            <div class="d-flex w-100 justify-content-between">
                                <span>{{ $filterRegion->name }}</span>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="[yandex][{{ $filterRegion->id }}]" data-region-id="{{ $filterRegion->id }}" checked="checked">
                                </div>
                            </div>
                        </li>
                    @endforeach
                    @foreach ($task->ready_scheduled_position->get_region() as  $scheduled_position)
                        @if(array_keys($scheduled_position)[0] == "yandex")
                                <li class="list-group-item region--list">
                                    <div class="d-flex w-100 justify-content-between">
                                        <span>{{ $scheduled_position['yandex']['name'] }}</span>
                                        <div class="custom-control custom-switch google-switch">
                                            <input type="checkbox" name="[yandex][{{ $scheduled_position['yandex']->id }}]" data-region-id="{{$scheduled_position['yandex']->id }}" checked="checked">
                                        </div>
                                    </div>
                                </li>
                        @endif
                    @endforeach
                </ul>

                <hr/>

                <ul class="list-group google-setup list-group-sm" data-search-list="google">
                    <li class="list-group-item bg-info list-group-item-main">
                        <div class="d-flex w-100 justify-content-between">
                            <span>{{ __('Google') }}</span>
                            <div class="custom-control custom-switch">
                                <button type="button" class="btn btn-sm btn-primary add-region--button1">{{ __('+ Region') }}</button>
                                <input type="checkbox" name="google-search" class="google-search" checked="checked">
                            </div>
                        </div>
                    </li>
                    @foreach ($group_regions as $filterRegion)
                        <li class="list-group-item region--list">
                            <div class="d-flex w-100 justify-content-between">
                                <span>{{ $filterRegion->name }}</span>
                                <div class="custom-control custom-switch google-switch">
                                    <input type="checkbox" name="[google][{{ $filterRegion->id }}]" data-region-id="{{ $filterRegion->id }}" checked="checked">
                                </div>
                            </div>
                        </li>
                    @endforeach

                    @foreach ($task->ready_scheduled_position->get_region() as  $scheduled_position)
                        @if(array_keys($scheduled_position)[0] == "google")
                            <li class="list-group-item region--list">
                                <div class="d-flex w-100 justify-content-between">
                                    <span>{{ $scheduled_position['google']['name'] }}</span>
                                    <div class="custom-control custom-switch google-switch">
                                        <input type="checkbox" name="[yandex][{{ $scheduled_position['google']->id }}]" data-region-id="{{$scheduled_position['google']->id }}" checked="checked">
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach

                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary refresh-all-position--button1">{{ __('Refresh all') }}</button>
            <button type="button" class="btn btn-primary refresh-position--button1">{{ __('Refresh by filter') }}</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
        </div>
    </div>


@endsection
