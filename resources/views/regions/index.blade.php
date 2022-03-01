@extends('includes.main')

@section('title')
    {{ __('Project Position') }}
@endsection

@section('sidebar')
    @auth
        @include('includes.sidebar')
    @endauth
@endsection


@section('content')
    <h1>Регионы</h1>
@endsection
