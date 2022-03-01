@extends('includes.main')

@section('title')
    {{ __('Home Page') }}
@endsection

{{-- @section('sidebar')
  @auth
    @include('includes.sidebar')
  @endauth
@endsection --}}

@section('content')
    <form method="DELETE" action="{{ route('project.multyplydestroy') }}" id="multDelite"></form>
    <form method="DELETE" action="{{ route('project.multyplyarchive') }}" id="multarchive"></form>
    <div class="container">
        <div class="row mt-4 query-content--container">

            <div class="col-xl-12">
                <div class="project__title d-flex align-items-center">
                    <h2 class="mb-0">{{ __('Мои  проекты') }}</h2>
                    <a href="#" class="ml-3" data-toggle="modal" data-target="#addproject">
                        <img src="{{ asset('/public/images/project/plus.png') }}" class="" alt="">
                    </a>

                </div>

                <div class="select__project d-flex align-items-center">
                    <div class="select__project--search position-relative d-flex align-items-center">
                        <input type="search" placeholder="Поиск по проектам" class="query-search--input" />
                        <div class="select__project--count">Выбрано: <span>0</span></div>
                    </div>
                    <div class="select__project--delete-reload-archive">
                        <a href="#" data-toggle="modal" data-target="#all-archive-project"
                            class="select__project-all-archive">
                            <img src="{{ asset('/public/images/project/select_project/archive.svg') }}" alt="">
                        </a>
                        <a href="#" data-toggle="modal" data-target="#all-delete-project">
                            <img src="{{ asset('/public/images/project/select_project/delete.svg') }}" alt="">
                        </a>
                    </div>
                </div>


                @if (count($projects) < 1)
                    <div class="alert alert-dark" role="alert">
                        {{ __('No Data') }}
                    </div>
                @else


                    {{ $projects->links() }}
                    <table class="w-100 select__project--table mt-4">
                        <thead>
                            <tr>
                                <th></th>
                                <th scope="col">{{ __('Название проекта') }}</th>
                                <th scope="col">{{ __('Запросов') }}</th>
                                <th scope="col">{{ __('Ср. позиция') }}</th>
                                <th scope="col">
                                    <div class="d-flex justify-content-center">
                                        {{ __('Динамика') }}
                                        <div class="select__project--table-icon">
                                            <img src="{{ asset('/public/images/project/select_project/up_rating.svg') }}"
                                                alt=""> /
                                            <img src="{{ asset('/public/images/project/select_project/equals.svg') }}"
                                                alt=""> /
                                            <img src="{{ asset('/public/images/project/select_project/down_rating.svg') }}"
                                                alt="">
                                        </div>
                                    </div>
                                </th>
                                <th scope="col">{{ __('ТОП 3 / 10 / 30 / 50 / 100') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($projects as $project)
                                @if (auth()->user()->has_client())
                                    @if (has_clientby($project->id, auth()->user()->client->id))
                                        <tr
                                            class="{{ $project->is_active == 0 ? 'text-muted' : '' }} table-group-item open">
                                            <td class="select__project--checkbox">
                                                <input type="checkbox"
                                                    id="select__project--checkbox-checked-{{ $project->id }}">
                                                <label
                                                    for="select__project--checkbox-checked-{{ $project->id }}"></label>
                                            </td>
                                            <td class="select__project--name position-relative">
                                                <div class="d-flex align-items-center">
                                                    <div class="select__project--table-title">
                                                        @if ($project->is_active == 1)
                                                            <a
                                                                href="{{ route('project.select', [$project->id]) }}">{{ $project->name }}</a>
                                                        @else
                                                            {{ $project->name }}
                                                        @endif
                                                    </div>
                                                    <div
                                                        class="select__project--table-date-reload d-flex align-items-center ml-1">
                                                        <a href="#" class="mr-1">
                                                            <img src="{{ asset('/public/images/project/select_project/reload.svg') }}"
                                                                alt="">
                                                        </a>
                                                        {{ $project->latest_date == null ? __('No Data') : date('d.m.Y', strtotime($project->latest_date)) }}
                                                    </div>
                                                </div>
                                                <div class="select__project--table-url">
                                                    {{ $project->url }}
                                                </div>
                                                <div class="select__project--table-setting">
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/home_icon.svg') }}"
                                                            alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/app_icon.svg') }}"
                                                            alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/progress.svg') }}"
                                                            alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/list.svg') }}" alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/map.svg') }}" alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/time.svg') }}" alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/user.svg') }}" alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/document.svg') }}"
                                                            alt="">
                                                    </a>
                                                    <a href="#">
                                                        <img src="{{ asset('/public/images/project/setting.svg') }}"
                                                            alt="">
                                                    </a>
                                                </div>

                                            </td>

                                            <td class="select__project--requests  position-relative">104 845</td>

                                            <td class="select__project--middle-position  position-relative">
                                                <div
                                                    class="select__project--middle-position-req d-flex align-items-center flex-column">

                                                    <div
                                                        class="select__project--middle-position-req-yandex d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/yandex_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2"> 48</div>
                                                        <div class="select__project--table-rating-up">2</div>
                                                    </div>

                                                    <div
                                                        class="select__project--middle-position-req-google d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/google_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">48</div>
                                                        <div class="select__project--table-rating-up">2</div>
                                                    </div>


                                                </div>
                                                {{-- {{ $project->average_position == null ? __('No Data') : $project->average_position }} --}}
                                            </td>

                                            <td class="select__project--dynamic flex  flex-column  position-relative">
                                                <div
                                                    class="select__project--dynamic-yandex-req d-flex align-items-center justify-content-center">

                                                    <div
                                                        class="select__project--table-dynamic-req-yandex-up  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/yandex_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">48</div>
                                                        <div class="select__project--table-rating-up">2</div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--table-dynamic-req-yandex-equals d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 48</div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--table-dynamic-req-yandex-down d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 48</div>
                                                        <div class="select__project--table-rating-down">2</div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="select__project--dynamic-google-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--table-dynamic-req-google-up  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/google_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">48</div>
                                                        <div class="select__project--table-rating-up">2</div>
                                                    </div>
                                                    <span class="select__project--delemitr"> / </span>
                                                    <div
                                                        class="select__project--table-dynamic-req-google-equals d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 48</div>
                                                    </div>
                                                    <span class="select__project--delemitr"> / </span>
                                                    <div
                                                        class="select__project--table-dynamic-req-google-down d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 48</div>
                                                        <div class="select__project--table-rating-down">2</div>
                                                    </div>
                                                </div>
                                            </td>


                                            <td class="select__project--top  position-relative">

                                                <div
                                                    class="select__project--top-yandex-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--top-yandex-req-3  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/yandex_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request ml-2">30</div>
                                                        <div class="select__project--table-rating-up">2</div>
                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-10 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 30</div>
                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-30 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 30</div>
                                                        <div class="select__project--table-rating-down">2</div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>


                                                    <div
                                                        class="select__project--top-yandex-req-50 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 30</div>
                                                        <div class="select__project--table-rating-down">2</div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-100 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 30</div>
                                                        <div class="select__project--table-rating-down">2</div>
                                                    </div>
                                                </div>

                                                <div
                                                    class="select__project--top-yandex-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--top-yandex-req-3  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/google_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request ml-2">12</div>

                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-10 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 543</div>
                                                        <div class="select__project--table-rating-down">20</div>
                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-30 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">2</div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>


                                                    <div
                                                        class="select__project--top-yandex-req-50 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request"> 458</div>
                                                        <div class="select__project--table-rating-up">20</div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-100 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">425</div>
                                                        <div class="select__project--table-rating-up">20</div>
                                                    </div>
                                                </div>


                                            </td>
                                            <td class="select__project--setting position-relative">
                                                <a href="#" class="select__project--setting-link">
                                                    <img src="{{ asset('/public/images/project/setting_dotts.svg') }}"
                                                        alt="">
                                                </a>
                                                <div class="select__project--setting-dropdown">
                                                    <a href="#">Запустить съём</a>
                                                    <a href="#">Дать доступ</a>
                                                    <a href="#">Настройки</a>
                                                    <a href="{{ route('project.archive', $project->id) }}">
                                                        @if ($project->is_active)
                                                            {{ 'Архивировать' }}
                                                        @else
                                                            {{ 'Разархивировать' }}
                                                        @endif
                                                    </a>
                                                    <a href="" class="select__project--setting-dropdown-delete">Удалить</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @else
                                    <tr
                                        class="{{ $project->is_active == 0 ? 'text-muted' : '' }} table-group-item open">
                                        {{-- Чекбоксы --}}
                                        <td class="select__project--checkbox">
                                            <input type="checkbox" name="ids[]" value="{{ $project->id }}"
                                                data-active="{{ $project->is_active }}"
                                                data-name="{{ $project->name }}"
                                                id="select__project--checkbox-checked-{{ $project->id }}">
                                            <label for="select__project--checkbox-checked-{{ $project->id }}"></label>
                                        </td>
                                        {{-- ---- --}}

                                        {{-- Название проектов и прочая информация --}}
                                        <td class="select__project--name position-relative">
                                            <div class="d-flex align-items-center">
                                                <div class="select__project--table-title">
                                                    @if ($project->is_active == 1)
                                                        <a
                                                            href="{{ route('project.position', [$project->id]) }}">{{ $project->name }}</a>
                                                    @else
                                                        {{ $project->name }}
                                                    @endif
                                                </div>
                                                <div
                                                    class="select__project--table-date-reload d-flex align-items-center ml-1">

                                                    @if ($project->is_active == 1)
                                                        <a href="#" class="mr-1">
                                                            <img src="{{ asset('/public/images/project/select_project/reload.svg') }}"
                                                                alt="">
                                                        </a>
                                                        <div class="mt-1">
                                                            {{ $project->positions_created_at_max == null ? __('No Data') : date('d.m.Y', strtotime($project->positions_created_at_max)) }}
                                                        </div>
                                                    @else
                                                        <span class="select__project--table-archive">В архиве</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="select__project--table-url">
                                                {{ $project->url }}
                                            </div>
                                            <div class="select__project--table-setting">
                                                <a href="{{ route('project.brief', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/home_icon.svg') }}"
                                                        alt="">
                                                </a>
                                                <a href="{{ route('query-group.index', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/app_icon.svg') }}" alt="">
                                                </a>
                                                <a href="{{ route('project.position', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/progress.svg') }}" alt="">
                                                </a>
                                                <a href="{{ route('project.summary', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/list.svg') }}" alt="">
                                                </a>
                                                <a href="{{ route('project.schedule', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/time.svg') }}" alt="">
                                                </a>
                                                <a href="{{ route('project.region.index', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/map.svg') }}" alt="">
                                                </a>
                                                <a href="{{ route('project.competitor.index', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/user.svg') }}" alt="">
                                                </a>

                                                <a href="{{ route('project.reports', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/document.svg') }}" alt="">
                                                </a>
                                                <a href="{{ route('project.settings', [$project->id]) }}">
                                                    <img src="{{ asset('/public/images/project/setting.svg') }}" alt="">
                                                </a>
                                            </div>

                                        </td>
                                        {{-- ---- --}}

                                        {{-- Общее количество запросов --}}
                                        <td class="select__project--requests  position-relative">
                                            {{ $project->queries_count }}
                                        </td>
                                        {{-- ---- --}}

                                        {{-- Средняя позиция яндекса и гугла --}}

                                        <td class="select__project--middle-position  position-relative">

                                            <div
                                                class="select__project--middle-position-req d-flex align-items-center flex-column">
                                                {{-- Средняя позиция Яндекса --}}
                                                @if (isset($statistics['yandex'][$project->id]) && !is_null($statistics['yandex'][$project->id]->statistics))
                                                    <div
                                                        class="select__project--middle-position-req-yandex d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/yandex_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">
                                                            {{ round($statistics['yandex'][$project->id]->statistics->average) }}
                                                        </div>

                                                        @if ($statistics['yandex'][$project->id]->statistics->total_change > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ round(abs($statistics['yandex'][$project->id]->statistics->total_change)) }}
                                                            </div>
                                                        @else
                                                            <div class="select__project--table-rating-down">
                                                                {{ round(abs($statistics['yandex'][$project->id]->statistics->total_change)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                {{-- ------------------------ --}}

                                                {{-- Средняя позиция Гугла --}}
                                                @if (isset($statistics['google'][$project->id]) && !is_null($statistics['google'][$project->id]->statistics))
                                                    <div
                                                        class="select__project--middle-position-req-google d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/google_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">
                                                            {{ round($statistics['google'][$project->id]->statistics->average) }}
                                                        </div>
                                                        @if ($statistics['google'][$project->id]->statistics->total_change > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ round(abs($statistics['google'][$project->id]->statistics->total_change)) }}
                                                            </div>
                                                        @else
                                                            <div class="select__project--table-rating-down">
                                                                {{ round(abs($statistics['google'][$project->id]->statistics->total_change)) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                {{-- ------------------------ --}}

                                            </div>
                                        </td>
                                        {{-- ---- --}}

                                        {{-- Динамика яндекса и гугла --}}

                                        <td class="select__project--dynamic flex  flex-column  position-relative">
                                            @if (isset($statistics['yandex'][$project->id]) && $statistics['yandex'][$project->id]->statistics)
                                                <div
                                                    class="select__project--dynamic-yandex-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--table-dynamic-req-yandex-up  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/yandex_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">
                                                            {{ $statistics['yandex'][$project->id]->statistics->decrease }}
                                                        </div>
                                                        <div class="select__project--table-rating-up">
                                                            {{ $statistics['yandex'][$project->id]->statistics->decrease_perecent }}
                                                        </div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--table-dynamic-req-yandex-equals d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['yandex'][$project->id]->statistics->unchanged }}
                                                        </div>
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--table-dynamic-req-yandex-down d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['yandex'][$project->id]->statistics->increase }}
                                                        </div>
                                                        <div class="select__project--table-rating-down">
                                                            {{ $statistics['yandex'][$project->id]->statistics->increase_perecent }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif


                                            @if (isset($statistics['google'][$project->id]) && $statistics['google'][$project->id]->statistics)
                                                <div
                                                    class="select__project--dynamic-google-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--table-dynamic-req-google-up  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/google_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request  ml-2">
                                                            {{ $statistics['google'][$project->id]->statistics->decrease }}
                                                        </div>
                                                        <div class="select__project--table-rating-up">
                                                            {{ $statistics['google'][$project->id]->statistics->decrease_perecent }}
                                                        </div>
                                                    </div>
                                                    <span class="select__project--delemitr"> / </span>
                                                    <div
                                                        class="select__project--table-dynamic-req-google-equals d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['google'][$project->id]->statistics->unchanged }}
                                                        </div>
                                                    </div>
                                                    <span class="select__project--delemitr"> / </span>
                                                    <div
                                                        class="select__project--table-dynamic-req-google-down d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['google'][$project->id]->statistics->increase }}
                                                        </div>
                                                        <div class="select__project--table-rating-down">
                                                            {{ $statistics['google'][$project->id]->statistics->increase_perecent }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        {{-- ---- --}}

                                        {{-- ТОП 3 / 10 / 30 / 50 / 100	 яндекса и гугла --}}
                                        <td class="select__project--top  position-relative">
                                            @if (isset($statistics['yandex'][$project->id]) && $statistics['yandex'][$project->id]->statistics)
                                                <div
                                                    class="select__project--top-yandex-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--top-yandex-req-3  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/yandex_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request ml-2">
                                                            {{ $statistics['yandex'][$project->id]->statistics->total_first_1_3 }}
                                                        </div>
                                                        @if ($statistics['yandex'][$project->id]->statistics->total_1_3 - $statistics['yandex'][$project->id]->statistics->total_first_1_3 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ $statistics['yandex'][$project->id]->statistics->total_1_3 - $statistics['yandex'][$project->id]->statistics->total_first_1_3 }}
                                                            </div>
                                                        @elseif ($statistics['yandex'][$project->id]->statistics->total_1_3 - $statistics['yandex'][$project->id]->statistics->total_first_1_3 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['yandex'][$project->id]->statistics->total_1_3 - $statistics['yandex'][$project->id]->statistics->total_first_1_3) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-10 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['yandex'][$project->id]->statistics->total_first_1_10 }}
                                                        </div>
                                                        @if ($statistics['yandex'][$project->id]->statistics->total_1_10 - $statistics['yandex'][$project->id]->statistics->total_first_1_10 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ $statistics['yandex'][$project->id]->statistics->total_1_10 - $statistics['yandex'][$project->id]->statistics->total_first_1_10 }}
                                                            </div>
                                                        @elseif ($statistics['yandex'][$project->id]->statistics->total_1_10 - $statistics['yandex'][$project->id]->statistics->total_first_1_10 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['yandex'][$project->id]->statistics->total_1_10 - $statistics['yandex'][$project->id]->statistics->total_first_1_10) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-30 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['yandex'][$project->id]->statistics->total_first_11_30 }}
                                                        </div>
                                                        @if ($statistics['yandex'][$project->id]->statistics->total_11_30 - $statistics['yandex'][$project->id]->statistics->total_first_11_30 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ $statistics['yandex'][$project->id]->statistics->total_11_30 - $statistics['yandex'][$project->id]->statistics->total_first_11_30 }}
                                                            </div>
                                                        @elseif ($statistics['yandex'][$project->id]->statistics->total_11_30 - $statistics['yandex'][$project->id]->statistics->total_first_11_30 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['yandex'][$project->id]->statistics->total_11_30 - $statistics['yandex'][$project->id]->statistics->total_first_11_30) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>


                                                    <div
                                                        class="select__project--top-yandex-req-50 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['yandex'][$project->id]->statistics->total_first_31_50 }}
                                                        </div>
                                                        @if ($statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ $statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50 }}
                                                            </div>
                                                        @elseif ($statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-100 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request">
                                                            {{ $statistics['yandex'][$project->id]->statistics->total_first_51_100 }}
                                                        </div>
                                                        @if ($statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ $statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50 }}
                                                            </div>
                                                        @elseif ($statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['yandex'][$project->id]->statistics->total_31_50 - $statistics['yandex'][$project->id]->statistics->total_first_31_50) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if (isset($statistics['google'][$project->id]) && $statistics['google'][$project->id]->statistics)
                                                <div
                                                    class="select__project--top-yandex-req d-flex align-items-center justify-content-center">
                                                    <div
                                                        class="select__project--top-yandex-req-3  d-flex justify-content-center">
                                                        <img src="{{ asset('/public/images/project/google_position.svg') }}"
                                                            alt="">
                                                        <div class="select__project--table-google-request ml-2">
                                                            {{ $statistics['google'][$project->id]->statistics->total_first_1_3 }}
                                                        </div>
                                                        @if ($statistics['google'][$project->id]->statistics->total_1_3 - $statistics['google'][$project->id]->statistics->total_first_1_3 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_1_3 - $statistics['google'][$project->id]->statistics->total_first_1_3) }}
                                                            </div>
                                                        @elseif ($statistics['google'][$project->id]->statistics->total_1_3 - $statistics['google'][$project->id]->statistics->total_first_1_3 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_1_3 - $statistics['google'][$project->id]->statistics->total_first_1_3) }}
                                                            </div>
                                                        @else
                                                        @endif

                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-10 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request ml-2">
                                                            {{ $statistics['google'][$project->id]->statistics->total_first_1_10 }}
                                                        </div>
                                                        @if ($statistics['google'][$project->id]->statistics->total_1_10 - $statistics['google'][$project->id]->statistics->total_first_1_10 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_1_10 - $statistics['google'][$project->id]->statistics->total_first_1_10) }}
                                                            </div>
                                                        @elseif ($statistics['google'][$project->id]->statistics->total_1_10 - $statistics['google'][$project->id]->statistics->total_first_1_10 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_1_10 - $statistics['google'][$project->id]->statistics->total_first_1_10) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>


                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-30 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request ml-2">
                                                            {{ $statistics['google'][$project->id]->statistics->total_first_11_30 }}
                                                        </div>
                                                        @if ($statistics['google'][$project->id]->statistics->total_11_30 - $statistics['google'][$project->id]->statistics->total_first_11_30 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_11_30 - $statistics['google'][$project->id]->statistics->total_first_11_30) }}
                                                            </div>
                                                        @elseif ($statistics['google'][$project->id]->statistics->total_11_30 - $statistics['google'][$project->id]->statistics->total_first_11_30 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_11_30 - $statistics['google'][$project->id]->statistics->total_first_11_30) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>


                                                    <div
                                                        class="select__project--top-yandex-req-50 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request ml-2">
                                                            {{ $statistics['google'][$project->id]->statistics->total_first_31_50 }}
                                                        </div>
                                                        @if ($statistics['google'][$project->id]->statistics->total_31_50 - $statistics['google'][$project->id]->statistics->total_first_31_50 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_31_50 - $statistics['google'][$project->id]->statistics->total_first_31_50) }}
                                                            </div>
                                                        @elseif ($statistics['google'][$project->id]->statistics->total_31_50 - $statistics['google'][$project->id]->statistics->total_first_31_50 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_31_50 - $statistics['google'][$project->id]->statistics->total_first_31_50) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>

                                                    <span class="select__project--delemitr"> / </span>

                                                    <div
                                                        class="select__project--top-yandex-req-100 d-flex justify-content-center">
                                                        <div class="select__project--table-google-request ml-2">
                                                            {{ $statistics['google'][$project->id]->statistics->total_first_51_100 }}
                                                        </div>
                                                        @if ($statistics['google'][$project->id]->statistics->total_51_100 - $statistics['google'][$project->id]->statistics->total_first_51_100 > 0)
                                                            <div class="select__project--table-rating-up">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_51_100 - $statistics['google'][$project->id]->statistics->total_first_51_100) }}
                                                            </div>
                                                        @elseif ($statistics['google'][$project->id]->statistics->total_51_100 - $statistics['google'][$project->id]->statistics->total_first_51_100 < 0)
                                                            <div class="select__project--table-rating-down">
                                                                {{ abs($statistics['google'][$project->id]->statistics->total_51_100 - $statistics['google'][$project->id]->statistics->total_first_51_100) }}
                                                            </div>
                                                        @else
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                        </td>
                                        {{-- ---- --}}

                                        <td class="select__project--setting position-relative">
                                            <a href="#" class="select__project--setting-link">
                                                <img src="{{ asset('/public/images/project/setting_dotts.svg') }}"
                                                    alt="">
                                            </a>
                                            <div class="select__project--setting-dropdown">
                                                @if ($project->is_active == 1)
                                                    <a href="#">Запустить съём</a>
                                                    <a href="#">Дать доступ</a>
                                                    <a
                                                        href="{{ route('project.settings', [$project->id]) }}">Настройки</a>

                                                @endif
                                                <a class="select__project--edit" href="#" data-toggle="modal"
                                                    data-id="{{ $project->id }}" data-name="{{ $project->name }}"
                                                    data-route="{{ route('project.update', [$project->id]) }}"
                                                    data-url="{{ $project->url }}"
                                                    data-target="#editproject{{ $project->id }}">Редактировать</a>

                                                <a class="select__project--archive" data-id="{{ $project->id }}"
                                                    data-name="{{ $project->name }}" href="#" data-toggle="modal"
                                                    data-target="#archiveproject{{ $project->id }}"
                                                    data-active="{{ $project->is_active }}"
                                                    data-route="{{ route('project.archive', [$project->id]) }}">
                                                    @if ($project->is_active)
                                                        {{ 'Архивировать' }}
                                                    @else
                                                        {{ 'Разархивировать' }}
                                                    @endif
                                                </a>

                                                <a data-id="{{ $project->id }}" data-name="{{ $project->name }}"
                                                    href="#" data-toggle="modal"
                                                    data-target="#deleteproject{{ $project->id }}"
                                                    data-route="{{ route('project.destroy', [$project->id]) }}"
                                                    class="select__project--setting-dropdown-delete">Удалить</a>

                                            </div>
                                        </td>

                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('modal-section')
    @include('project.modal-content')
@endsection
