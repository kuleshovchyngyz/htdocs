<div class="modal-content__hidden modal-content--destroy-query">
	<div class="modal-title">
		{{__('Delete Query')}}
	</div>

	<div class="modal-content">
		{{__('Do you really want to delete this query?')}}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--archive-query">
	<div class="modal-title">{{ __('Archive Query') }}</div>
	<div class="modal-content">
	    {{ __('Do you really want to archive this query?') }}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--mass-store-query">
	<div class="modal-title">{{ __('Import Queries') }}</div>
	<div class="modal-content">
        <form action="{{ route('query.mass-assign') }}" method="post">
            @csrf
            <div class="form-group">
                <label>{{__('Queries (each query to new line)') }}</label>
                <textarea class="form-control query-import-list" name="name"></textarea>
            </div>
        </form>
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>


<div class="modal-content__hidden modal-content--assign-query-region">
	<div class="modal-title">
		{{ __('Assign Region') }}
	</div>

	<div class="modal-content">
        <form action="{{ route('query.assign-region') }}" method="post">
            @csrf
            <div class="form-group">
                <label>{{__('Region') }}</label>
                <select class="custom-select" name="region_id">
                    <option value="0">{{ __('No Region') }}</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->region->id }}">{{ $region->region->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>
	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
    </div>
</div>








<div class="modal-content__hidden modal-content--target-query-group">
	<div class="modal-title">
		{{ __('Assign Target Page') }}
	</div>

	<div class="modal-content">
            @csrf
            <div class="form-group">
                <label>{{__('Target Page') }}</label>
					<input id="target_path_input" type="text" class="form-control " name="target_path"  required="">
            </div>

	</div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
    </div>
</div>
