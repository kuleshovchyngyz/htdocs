<nav id="sidebar">
    <ul class="nav flex-column">
        @if (isset($selected_project))


            @if (!auth()->user()->hasRole('client'))
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.brief') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.brief', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/home.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Сводка по проекту') }} </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('query-group.index') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('query-group.index', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/app.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Ядро проекта') }}</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.position') ? 'sidebar__current-point' : '' }}"" href="
                        {{ route('project.position', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/position.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Capture Positions') }}</div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.summary') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.summary', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/list.svg') }}" alt="">
                        <div class="nav-link-title"> Сводка по регионам </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.schedule') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.schedule', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/time.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Schedule') }} </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.region.index') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.region.index', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/map.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Regions') }} </div>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ Route::is('regions') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('regions') }}">
                        <img src="{{ asset('/public/images/sidebar/map.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Регионы') }} </div>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.competitor.index') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.competitor.index', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/rivel.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Competitors') }} </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.reports') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.reports', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/reports.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Отчеты') }} </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('project.settings') ? 'sidebar__current-point' : '' }}"
                        href="{{ route('project.settings', $selected_project->id) }}">
                        <img src="{{ asset('/public/images/sidebar/setting.svg') }}" alt="">
                        <div class="nav-link-title"> {{ __('Настройки') }} </div>
                    </a>
                </li>

            @endif
        @endif
    </ul>
    <a id="toggle-sidebar" href="#">
        <img src="{{ asset('/public/images/sidebar/turn.svg') }}" alt="" class="turn">
        <img src="{{ asset('/public/images/sidebar/expand.svg') }}" alt="" class="expand">
    </a>
</nav>
