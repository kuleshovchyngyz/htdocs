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
                <h1>{{ 'Список клиентов' }}</h1>
            </div>
            <div class="col-2 text-right">
                <a class="btn btn-primary" title="{{  __('Create Region') }}" href="{{ route('client.create') }}">{{ __('Add') }}</a>
            </div>


            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">{{__('Name')}}</th>
                    <th scope="col">{{__('Projects')}}</th>
                    <th scope="col">{{'Действия'}}</th>

                </thead>
                <tbody>
                @foreach(\App\User::all() as $user)
                    @if($user->hasRole('client'))
                    <tr>
                        <th scope="row">{{ $user->email }}</th>
                        <th scope="row">{{ $user->client->project_names() }}</th>

                        <td class="text-right">
                            <a href="{{ route('client.edit', [$user->client->id]) }}" title="{{ __('Edit') }}" class="edit-region--link"><i class="fas fa-edit"></i></a>
                            <a href="javascript:void(0)" title="{{ __('Delete') }}" data-client-id="{{ $user->client->id }}" class="destroy-client--link"><i class="fas fa-times"></i></a>

                        </td>
                    </tr>
                    @endif
                @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('modal-section')
    @include('clients.modal-content')
@endsection
