@extends('includes.main')

@section('title')
    {{ __('Edit Query') }}
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
                <div class="card-header"><h1>{{ __('Edit Query') }}</h1></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('query.update', $item->id) }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Query Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $item->name }}" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('Query Group') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select @error('query_group_id') is-invalid @enderror" name="query_group_id">
                                    <option value="0">{{ __('Please select') }}</option>
                                    @foreach ($query_groups as $query_group)
                                        <option value="{{ $query_group->id }}" {{$item->query_group_id == $query_group->id ? 'selected' : ''}}>{{ $query_group->name }}</option>
                                    @endforeach
                                </select>
                                @error('query_group_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="url" class="col-md-4 col-form-label text-md-right">{{ __('Region') }}</label>

                            <div class="col-md-6">
                                <select class="custom-select @error('region_id') is-invalid @enderror" name="region_id">
                                    <option value="0">{{ __('No Region') }}</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}" {{$item->region_id == $region->id ? 'selected' : ''}}>{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                @error('region_id')
                                    <span class="invalid-feedback">
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
