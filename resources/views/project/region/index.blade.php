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
    <div class="content-wrapper container-fluid project-region-icon">
        <div class="row">
            {{-- <div class="col-10">
                <h1>{{ __('Project Region List') }}</h1>
                <p>{{ __('List of all project regions') }}</p>
            </div>
            <div class="col-2 text-right">
                <a class="btn btn-primary" title="{{ __('Create Project Region') }}"
                    href="{{ route('project.region.create') }}">{{ __('Add') }}</a>
            </div>
            <div class="col-xl">


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
            @if (count($items) < 1)
                <div class="alert alert-dark" role="alert">
                    {{ __('No Data') }}
                </div>
            @else
                {{ $items->links() }}
                <div class="col-lg-6">
                    <div class="project-region-block bg-white">
                        <div class="project-region__title-setting d-flex justify-content-between">
                            <div class="project-region-title">
                                <div>Регионы</div>
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
                            <th scope="col">{{ __('URL') }}</th>
                            <th scope="col">{{ __('Region') }}</th>
                            <th scope="col">{{ __('Archived') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="{{ $item->is_active == 0 ? 'text-muted' : '' }}">
                                <td scope="row">
                                    {{ $item->url }}
                                </td>
                                <td>{{ $item->region->name }}</td>
                                <td>
                                    @if ($item->is_active == true)
                                        <span class="badge badge-success">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('project.region.edit', [$item->id]) }}"
                                        title="{{ __('Edit Project Region') }}"><i class="fas fa-edit"></i></a>
                                    <a href="javascript:void(0)" title="{{ __('Archive/Unarchive Project Region') }}"
                                        data-project-region-id="{{ $item->id }}"
                                        class="archive-project-region--link"><i class="fas fa-archive"></i></a>
                                    <a href="javascript:void(0)" title="{{ __('Delete Project Region') }}"
                                        data-project-region-id="{{ $item->id }}"
                                        class="destroy-project-region--link"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
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
