<div class="modal-content__hidden modal-content--store-query-group">
	<div class="modal-title">
		{{ __('Create Query Group') }}
	</div>

	<div class="modal-content">
		<div class="form-group">
		    <label>{{__('Group Name') }}</label>
		    <input type="text" class="form-control" name="name" autofocus>
	  	</div>
		<div class="form-group">
		    <label>{{__('Parent Query Group') }}</label>
		    <select class="custom-select" name="parent_group_id">
                <option value="0">{{ __('No Parent') }}</option>
                @foreach ($group_queries as $item)
	              	<option value="{{ $item['id'] }}">@php echo str_repeat('&nbsp;&nbsp;', $item['level']); @endphp {{ $item['name'] }}</option>
	            @endforeach
            </select>
	  	</div>
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('Create') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--destroy-query-group">
	<div class="modal-title">
		{{__('Delete Query Group')}}
	</div>

	<div class="modal-content">
		{{__('Do you really want to delete this query group?')}}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--archive-query-group">
	<div class="modal-title">{{ __('Archive/Unarchive Query Group') }}</div>
	<div class="modal-content">
	    {{ __('Do you really want to archive this query group?') }}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>
<div class="modal-content__hidden modal-content--unarchive-query-group">
	<div class="modal-title">{{ __('Unarchive Query Group') }}</div>
	<div class="modal-content">
	    {{ __('Do you really want to unarchive this query group?') }}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>



<div class="modal fade" id="uploadImportFile" tabindex="-1" role="dialog" aria-labelledby="uploadImportFile" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Выберите файл</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('query.import')}}" method="POST" enctype="multipart/form-data">
                @csrf


                    <div class="text-center">
                        <input type="file" name="file" class="form-control">
                    </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="submit" class="btn btn-primary">Импортировать</button>
            </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="SummaryOfImports" tabindex="-1" role="dialog" aria-labelledby="SummaryOfImports" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ Session::get('filename')  }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">Количество созданных групп:</div>
                        <div class="col-md-6 ml-auto">{{ Session::get('numberOfGroups')  }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">Количество импортируемых запросов:</div>
                        <div class="col-md-6 ml-auto">{{ Session::get('numberOfQueries')  }}</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
