@extends('includes.main')

@section('title')
  {{ __('Region List') }}
@endsection

@section('sidebar')
  @auth
    @include('includes.sidebar')
  @endauth
@endsection

@section('content')
<div class="content-wrapper container-fluid">
    <div class="row mt-5">
      <div class="col-10">
        <h1>{{ __('Region List') }}</h1>
      </div>
      <div class="col-2 text-right">
        <a class="btn btn-primary" title="{{  __('Create Region') }}" href="{{ route('region.create') }}">{{ __('Add') }}</a>
      </div>

      <div class="col-xl">
        {{ $items->links() }}

        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">{{__('Name')}}</th>
              <th scope="col">{{__('Yandex Index')}}</th>
              <th scope="col">{{__('Google Index')}}</th>
              <th scope="col">{{__('Actions')}}</th>
            </tr>
          </thead>
          <tbody>
                @foreach ($items as $item)
                <tr>
                  <th scope="row">{{ $item->name }}</th>
                  <th scope="row">{{ $item->yandex_index }}</th>
                  <th scope="row">{{ $item->google_index }}</th>
                  <td class="text-right">
                    <a href="{{ route('region.edit', [$item->id]) }}" title="{{ __('Edit Region') }}" class="edit-region--link"><i class="fas fa-edit"></i></a>
                    <a href="javascript:void(0)" title="{{ __('Delete Region') }}" data-region-id="{{ $item->id }}" class="destroy-region--link"><i class="fas fa-times"></i></a>

                  </td>
                </tr>
                @endforeach
          </tbody>
        </table>
      </div>
    </div>
</div>
@endsection

@section('modal-section')
  @include('region.modal-content')
@endsection
