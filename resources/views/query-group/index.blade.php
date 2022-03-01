@extends('includes.main')

@section('title')
    {{ __('Группы и запросы') }}
@endsection

@section('sidebar')
    @auth
        @include('includes.sidebar')
    @endauth
@endsection

@section('content')
    <div class="content-wrapper container-fluid query-group-index">
        <div class="row  mt-3">
            <div class="col-lg-12">
                <div class="query-group-index__icons">
                    <a href="#">
                        <img src="{{ asset('/public/images/query-group/fail.svg') }}" alt="">
                    </a>

                    <a href="#">
                        <img src="{{ asset('/public/images/query-group/app.svg') }}" alt="">
                    </a>

                    <a href="#">
                        <img src="{{ asset('/public/images/query-group/coin.svg') }}" alt="">
                    </a>

                    <a href="#">
                        <img src="{{ asset('/public/images/query-group/export.svg') }}" alt="">
                    </a>

                    <a href="#">
                        <img src="{{ asset('/public/images/query-group/import.svg') }}" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-lg-6">
                <div class="query-group-content--container">
                    <ul class="nav nav-tabs nav-justified md-tabs" id="queryGroupTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link d-flex flex-wrap align-items-center justify-content-between" role="tab"
                                aria-controls="home-just">
                                <div class="query-group-title">{{ __('Groups') }}</div>
                                <div class="icon-group text-right">
                                    {{-- Не работает --}}
                                    <button title="{{ __('Search') }}" class="btn disabled">
                                        <img src="{{ asset('/public/images/query-group/search.svg') }}" alt="">
                                    </button>
                                    {{-- Не работает --}}

                                    <button href="javascript:void(0)" title="{{ __('Add Query Group') }}"
                                        class="btn store-query-group--link"> <img
                                            src="{{ asset('/public/images/query-group/plus.svg') }}" alt=""> </button>

                                    <button href="javascript:void(0)" title="{{ __('Edit Query Group') }}"
                                        class="btn disabled  edit-query-group--link">
                                        <img src="{{ asset('/public/images/query-group/edit.svg') }}" alt=""></button>

                                    <button href="javascript:void(0)" title="{{ __('Target Page') }}"
                                        class="btn target-query-group--link"><img
                                            src="{{ asset('/public/images/query-group/link.svg') }}" alt=""></button>

                                    {{-- Не работает --}}
                                    <button title="{{ __('COin') }}" class="btn target-query-group--link"><img
                                            src="{{ asset('/public/images/query-group/coin-grey.svg') }}"
                                            alt=""></button>

                                    <button title="{{ __('Copy') }}" class="btn target-query-group--link"><img
                                            src="{{ asset('/public/images/query-group/copy.svg') }}" alt=""></button>
                                    {{-- Не работает --}}

                                    <button title="Импортировать запросы" data-toggle="modal"
                                        data-target="#uploadImportFile" class="btn"><img
                                            src="{{ asset('/public/images/query-group/arrow.svg') }}" alt=""></button>

                                    <button href="javascript:void(0)" title="{{ __('Archive Query Group') }}"
                                        class="btn disabled  archive-query-group--link"><img
                                            src="{{ asset('/public/images/query-group/archive.svg') }}" alt=""></button>
                                    <button href="javascript:void(0)" title="{{ __('Delete Query Group') }}"
                                        class="btn disabled  destroy-query-group--link"><img
                                            src="{{ asset('/public/images/query-group/delete.svg') }}" alt=""></button>



                                </div>
                                <div class="query-group-selected w-100 text-left">
                                    Выбрано: 3
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content  query-group-list--container">
                        <div class="tab-pane fade show active" aria-labelledby="home-tab-just">
                            @if (count($items) < 1)
                                <div class="alert alert-dark" role="alert">
                                    {{ __('No Data') }}
                                </div>
                            @else
                                @if (isset($items) && count($items) > 0)
                                    @include ('shared.tree_entry', ['entries' => $items, 'level' => 0])
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 query-content--container">
                <ul class="nav nav-tabs nav-justified md-tabs indigo" id="queryTabs" role="tablist">
                    <li class="nav-item ">
                        <a class="nav-link bg-white" role="tab" aria-controls="home-just">
                            <div class="d-flex justify-content-between">
                                <div class="query-content--container-title">
                                    IT Аутсорсинг
                                    (общие запросы)
                                    <div class="d-flex">
                                        <span>Запросов:21</span>
                                        <span>Выбрано: 20</span>
                                    </div>
                                </div>
                                <div class="icon-group text-right">
                                    {{-- Не работает --}}
                                    <button title="{{ __('Search') }}" class="btn disabled">
                                        <img src="{{ asset('/public/images/query-group/search.svg') }}" alt="">
                                    </button>
                                    {{-- Не работает --}}

                                    <button href="javascript:void(0)" title="{{ __('Add Query') }}"
                                        class="btn  disabled store-query--link">
                                        <img src="{{ asset('/public/images/setting_card/plus.svg') }}" alt="">
                                    </button>

                                    <button href="javascript:void(0)" title="{{ __('Assign Region') }}"
                                        class="btn  disabled region-query--link">
                                        <img src="{{ asset('/public/images/setting_card/map.svg') }}" alt="">
                                    </button>

                                    {{-- Не работает --}}
                                    <button title="{{ __('COin') }}" class="btn target-query-group--link">
                                        <img src="{{ asset('/public/images/query-group/coin-grey.svg') }}" alt="">
                                    </button>
                                    {{-- Не работает --}}

                                    <button href="javascript:void(0)" title="{{ __('Add Mass Query') }}"
                                        class="btn  disabled mass-store-query--link">
                                        <img src="{{ asset('/public/images/setting_card/arrow.svg') }}" alt="">
                                    </button>
                                    {{-- <button href="javascript:void(0)" title="{{ __('Edit Query') }}"
                                    class="btn  disabled edit-query--link"><i class="fas fa-edit"></i></button> --}}

                                    <button href="javascript:void(0)" title="{{ __('Archive Query') }}"
                                        class="btn  disabled archive-query--link">
                                        <img src="{{ asset('/public/images/setting_card/archive.svg') }}" alt="">
                                    </button>

                                    <button href="javascript:void(0)" title="{{ __('Delete Query') }}"
                                        class="btn  disabled destroy-query--link">
                                        <img src="{{ asset('/public/images/setting_card/delete.svg') }}" alt="">
                                    </button>

                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input id="select-all--checkbox" type="checkbox"
                                            aria-label="{{ __('All') }}">&nbsp;
                                        {{ __('Все запросы') }}

                                    </div>
                                </div>
                                <input type="text" class="form-control query-search--input"
                                    placeholder="{{ __('Search:') }}">
                            </div>

                        </a>
                    </li>
                </ul>
                <div class="tab-content card  query-list--container">
                    <div class="tab-pane fade show active" role="tabpanel">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!empty(Session::get('uploaded')))
        @push('scripts')
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#SummaryOfImports").modal("show");
                });
            </script>
        @endpush
    @endif
@endsection




@section('modal-section')
    @include('query-group.modal-content')
    @include('query.modal-content')
@endsection
