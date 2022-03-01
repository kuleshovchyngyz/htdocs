@extends('includes.main')

@section('title')
    {{ __('Project List') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row mt-4">
            <div class="col-xl-12">
                <div class="project__title d-flex align-items-center">
                    <h2 class="mb-0">{{ __('Мои  проекты') }}</h2>
                    <a href="" class="ml-3">
                        <img src="{{ asset('/public/images/project/plus.png') }}" class="" alt="">
                    </a>

                </div>
                @if (count($projects) < 1)
                    <div class="alert alert-dark" role="alert">
                        {{ __('No Data') }}
                    </div>
                @else
                    {{ $projects->links() }}
                    <table class="table mt-4">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">{{ __('Project') }}</th>
                                <th scope="col">{{ __('Average Position') }}</th>
                                <th scope="col">{{ __('Last Updated') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                                @if (auth()->user()->has_client())
                                    @if (has_clientby($project->id, auth()->user()->client->id))
                                        <tr class="{{ $project->is_active == 0 ? 'text-muted' : '' }}">
                                            <th scope="row">
                                                @if ($project->is_active == 1)
                                                    <a
                                                        href="{{ route('project.select', [$project->id]) }}">{{ $project->name }}</a>
                                                @else
                                                    {{ $project->name }}
                                                @endif
                                            </th>
                                            <td>{{ $project->average_position == null ? __('No Data') : $project->average_position }}
                                            </td>
                                            <td>{{ $project->latest_date == null ? __('No Data') : $project->latest_date }}
                                            </td>
                                        </tr>

                                    @endif
                                @else
                                    <tr class="{{ $project->is_active == 0 ? 'text-muted' : '' }}">
                                        <th scope="row">
                                            @if ($project->is_active == 1)
                                                <a
                                                    href="{{ route('project.select', [$project->id]) }}">{{ $project->name }}</a>
                                            @else
                                                {{ $project->name }}
                                            @endif
                                        </th>
                                        <td>{{ $project->average_position == null ? __('No Data') : $project->average_position }}
                                        </td>
                                        <td>{{ $project->latest_date == null ? __('No Data') : $project->latest_date }}
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
