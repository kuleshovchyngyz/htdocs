@extends('includes.main')

@section('title')
    {{ __('Create Project Region') }}
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
                <div class="card-header"><h1>{{ __('Create Project Region') }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('project.region.update', $item->id) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('Region') }}</label>
                            <div class="col-md-6">
                                <select class="selectpicker" name="region_id" data-live-search="true"  data-max-options="10">
                                    @foreach($regions as $region)
                                    <option value="{{ $region->id }}"
                                    @if ($item->region_id == $region->id)
                                        selected
                                    @endif >{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('URL') }}</label>
                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ $item->url }}" required autocomplete="url">

                                @error('url')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4 text-md-right">{{ __('Archived') }}</div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="hidden" value="1" name="is_active">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="0" {{$item->is_active == "0" ? 'checked' : ''}}>
                                </div>
                                @error('is_active')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>

                        <div class="form-group row text-right">
                            <div class="col-md-10">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Update') }}
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
