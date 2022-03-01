@extends('includes.main')

@section('title')
    {{ __('Project Region List') }}
@endsection

@section('sidebar')
    @auth
        @include('includes.sidebar')
    @endauth
@endsection

@section('content')
    <div class="content-wrapper container-fluid competitor-bg">
        {{-- <div class="row mt-5">
       <div class="col-10">
            <h1>{{ __('Competitors') }}</h1>

        </div>
        <div class="col-2 text-right">
            <a class="btn btn-primary" title="{{  __('Create Project Region') }}" href="{{ route('project.competitor.create') }}">{{ __('Add') }}</a>
        </div>

    </div> --}}
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
        <div class="row">
            {{-- <div class="col-10">
                <h1>{{ __('Competitors  List') }}</h1>

            </div>

            <div class="col-xl">


            </div> --}}
            @if (count($items) < 0)
                <div class="alert alert-dark" role="alert">
                    {{ __('No Data') }}
                </div>
            @else
                <div class="col-lg-6">
                    <div class="project-region-block bg-white">
                        <div class="project-region__title-setting d-flex justify-content-between">
                            <div class="project-region-title">
                                <div>Конкуренты</div>
                                <span>Выбрано: 3</span>
                            </div>
                            <div class="project-region-setting">
                                <a href="#"><img src="{{ asset('/public/images/setting_card/search.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/plus.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/edit.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/archive.svg') }}" alt=""></a>
                                <a href="#"><img src="{{ asset('/public/images/setting_card/delete.svg') }}" alt=""></a>
                            </div>
                        </div>

                        @foreach ($items as $item)
                            <div
                                class="project-region__content d-flex justify-content-between {{ $item->is_active == 0 ? 'text-muted' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="project-region__content-checkbox">
                                        <input type="checkbox" name="ids[]" value="18" data-active="1"
                                            data-name="BitRaid.ru" id="select__project--checkbox-checked-18">
                                        <label for="select__project--checkbox-checked-18"></label>
                                    </div>
                                    <div class="project-region__content-title">
                                        {{ $item->region->name }}
                                    </div>

                                </div>
                                <div class="project-region__content-url">{{ $item->url }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                {{-- <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">{{ __('Competitors') }}</th>
                            <th scope="col">{{ __('URL') }}</th>
                            <th scope="col">{{ __('Regions') }}</th>
                            <th scope="col">{{ __('Archived') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="{{ $item->is_active == 0 ? 'text-muted' : '' }}">
                                <td scope="row">
                                    {{ $item->name }}
                                </td>
                                <td>{{ $item->url }}</td>
                                <td>

                                    <div class="dropdown">
                                        <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ __('Regions') }}
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            @foreach ($item->competitorregions as $key => $region)

                                                <a class="dropdown-item" href="#">{{ $region->region->name }}</a>

                                            @endforeach
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    @if ($item->is_active == 1)
                                        <span class="badge badge-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('project.competitor.edit', [$item->id]) }}"
                                        title="{{ __('Edit Competitor') }}"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('project.competitor.archive', $item->id) }}"
                                        title="{{ __('Archive/Unarchive') }}"><i class="fas fa-archive"></i></a>
                                    <a href="javascript:void(0)" title="{{ __('Delete') }}"
                                        data-project-competitor-id="{{ $item->id }}"
                                        class="destroy-project-competitor--link"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table> --}}
            @endif
        </div>
    </div>
@endsection

@section('modal-section')
    @include('project.region.modal-content')
@endsection
