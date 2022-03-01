@extends('includes.main')

@section('title')
    {{ __('Create Region') }}
@endsection

@section('sidebar')
    @auth
      @include('includes.sidebar')
  @endauth
@endsection

@section('content')


<div class="content-wrapper container-fluid">
    <div class="row mt-5">
        <div class="col-lg-8 offset-md-2">
            <div class="card">
                <div class="card-header"><h1>{{ __('Create Region') }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('region.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Region Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group row">
                            <label for="api" class="col-md-4 col-form-label text-md-right">{{ __('Api Name') }}</label>

                            <div class="col-md-6">
                                <input id="api" type="text" class="form-control @error('api') is-invalid @enderror" name="api" value="{{ old('api') }}" autofocus>

                                @error('api')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="yandex_index" class="col-md-4 col-form-label text-md-right">{{ __('Yandex Index') }}</label>

                            <div class="col-md-6">
                                <input id="yandex_index" type="text" class="form-control @error('yandex_index') is-invalid @enderror" name="yandex_index" value="{{ old('yandex_index') }}" autofocus>

                                @error('yandex_index')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="google_index" class="col-md-4 col-form-label text-md-right">{{ __('Google Index') }}</label>

                            <div class="col-md-6">
                                <input id="google_index" type="text" class="form-control @error('google_index') is-invalid @enderror" name="google_index" value="{{ old('google_index') }}" autofocus>

                                @error('google_index')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row text-right">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Create') }}
                                </button>

                                <a class="btn btn-secondary" href="{{ url()->previous() }}">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
