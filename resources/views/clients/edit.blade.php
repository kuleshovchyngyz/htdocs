@extends('includes.main')

@section('title')
    {{ __('Project List') }}
@endsection

@section('sidebar')
    @auth
        @include('includes.sidebar')
    @endauth
@endsection
@php($projects = \App\Project::all())
@section('content')

    <div class="content-wrapper container-fluid">
        <div class="row mt-5">
            <div class="col-10">

                <div class="row">
                    <div class="col-10">
                        <h1>{{ __('Project List') }}</h1>

                    </div>
                    <div class="col-2 text-right">
                        <a class="btn btn-primary" title="" href="{{ url()->previous() }}"><<{{ 'назад' }}</a>
                    </div>

                </div>


                    <h>
                        {{ __('List of all projects.') }}
                    </h>



                @if(count($projects)  < 1)
                    <div class="alert alert-dark" role="alert">
                        {{ __('No Data') }}
                    </div>
                @else

                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{__('Project')}}</th>
                            <th scope="col">{{__('Average Position')}}</th>
                            <th scope="col">{{__('Last Updated')}}</th>
                            <th scope="col">{{__('Actions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($projects as $project)
                            <tr class="{{ $project->is_active == 0 ? 'text-muted' : ''}}">
                                <th scope="row">
                                    @if ($project->is_active == 1)
                                        <a href="{{ route('project.select', [$project->id]) }}">{{ $project->name }}</a>
                                    @else
                                        {{ $project->name }}
                                    @endif
                                </th>
                                <td>{{ $project->average_position == null ? __('No Data') : $project->average_position }}</td>
                                <td>{{ $project->latest_date == null ? __('No Data') : $project->latest_date }}</td>
                                <td class="text-right">
                                    <input type="checkbox" class="project_of_client" id = "{{ $project->id }}" name="status" value="{{$client->id}}" {{ $project->client_($client->id)==true ? "checked" : "" }} >
                                </td>
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
    @include('project.modal-content')
@endsection
