@auth
    <nav class="bg__header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="bg__header--logo-dropdown d-flex align-items-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('/public/images/header/logo.png') }}" alt="" class="w-100 h-100">
                        </a>

                        <div class="dropdown">
                            @if (isset($selected_project))
                                <button class="project-name--button dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                    data-project-id="{{ $selected_project->id }}"><span>{{ $selected_project->name }}</span></button>
                            @else
                                <button class="project-name--button dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">{{ __('Выбор проекта') }}</button>
                            @endif

                            @if (count($all_projects) > 0)
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    @foreach ($all_projects as $project)
                                        @if (auth()->user()->has_client())
                                            @if ($project->client_(auth()->user()->client->id))
                                                <a class="dropdown-item"
                                                    href="{{ route('project.select', $project->id) }}">{{ $project->name }}</a>
                                            @endif
                                        @else
                                            <a class="dropdown-item"
                                                href="{{ route('project.select', $project->id) }}">{{ $project->name }}</a>
                                        @endif

                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if (!(Route::is('home') || Route::is('main')))
                            <div class="bg__header--name">
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg__header--logout-project d-flex justify-content-end">
                        <a href="{{ route('home') }}"> Мои проекты </a>
                        <a href="{{ route('logout') }}" class="ml-3"
                            onclick="event.preventDefault();
                                                                                                                                                                                                                                                                                                                document.getElementById('logout-form').submit();">
                            Выход</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
@endauth
