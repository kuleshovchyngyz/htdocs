@extends('includes.main')

@section('title')
    {{ __('Query List') }}
@endsection

@section('sidebar')
    @auth
      @include('includes.sidebar')
  @endauth
@endsection

@section('content')
<div class="content-wrapper container-fluid">
    <div class="row mt-5">
        <div class="col-xl">
            <h1>{{ __('Query List') }}</h1>
            <p>
                {{ __('List of all queries.') }}
            </p>

            @if(count($items)  < 1)
              <div class="alert alert-dark" role="alert">
                {{ __('No Data') }}
              </div>
            @else
            {{ $items->links() }}

            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th scope="col">{{__('Name')}}</th>
                  <th scope="col">{{__('Archived')}}</th>
                  <th scope="col">{{__('Group Name')}}</th>
                  <th scope="col">{{__('Actions')}}</th>
                </tr>
              </thead>
              <tbody>
                    @foreach ($items as $item)
                    <tr class="{{ $item->is_active == 0 ? 'text-muted' : ''}}">
                      <th scope="row">
                        {{ $item->name }}</th>
                      <td>{{ $item->is_active == '0' ? __('Yes') : __('No') }}</td>
                      <td>{{ $item->group->name == null ? __('No Data') : $item->group->name }}</td>
                      <td class="text-right">
                        <a href="{{ route('query.edit', [$item->id]) }}" title="{{ __('Edit Query') }}" class="edit-query--link"><i class="fas fa-edit"></i></a>
                        <a href="javascript:void(0)" title="{{ __('Archive Query') }}" data-query-id="{{ $item->id }}" class="archive-query--link"><i class="fas fa-archive"></i></a>
                        <a href="javascript:void(0)" title="{{ __('Delete Query') }}" data-query-id="{{ $item->id }}" class="destroy-query--link"><i class="fas fa-trash"></i></a>

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
  @include('query.modal-content')
@section('content')
