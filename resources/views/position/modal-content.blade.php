<div class="modal-content__hidden modal-content--refresh-all-position">
	<div class="modal-title">
		{{__('Refresh All Position')}}
	</div>

	<div class="modal-content">
		{{__('Do you really want to refresh all positions?')}}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--refresh-yandex-position">
	<div class="modal-title">
		{{__('Refresh Google Position')}}
	</div>

	<div class="modal-content">
		{{__('Do you really want to refresh google position?')}}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--refresh-google-position">
	<div class="modal-title">
		{{__('Refresh Google Position')}}
	</div>

	<div class="modal-content">
		{{__('Do you really want to refresh google position?')}}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--refresh-yandex-position">
	<div class="modal-title">
		{{__('Refresh Google Position')}}
	</div>

	<div class="modal-content">
		{{__('Do you really want to refresh google position?')}}
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>


<div class="modal-content__hidden modal-content--append-region">
	<div class="modal-title">
		{{__('Add Region')}}
	</div>
	<div class="modal-content">
        <?php
        //dump($region_to_filter);
        ?>

		<div class="form-group">
		    <label>{{__('Region') }}</label>
		    <select class="custom-select" name="region_id">
                <option value="0">{{ __('No Region') }}</option>
                @foreach ($group_regions as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach

            </select>
	  	</div>
	</div>

	<div class="modal-footer">
	    <button type="button" class="btn btn-primary">{{ __('OK') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

<div class="modal-content__hidden modal-content--refresh-popup-container">
	<div class="modal-title">
		{{__('Refresh Position')}}
	</div>
	<div class="modal-content">
		<div class="search-setup-container">

            <label for="filter_query_group_id">{{ __('Query Groups') }}: </label>
            <ul class="list-group query-group-setup list-group-sm mk">
                <li class="list-group-item bg-info list-group-item-main">
                    <div class="d-flex w-100 justify-content-between">
                      <span>{{ __('All Query Groups') }}  <i id="angledown1" class="fas fa-angle-down"></i><i id="angleright1" class="fas fa-angle-right d-none"></i></span>


                      <div class="custom-control custom-switch yandex-switch">
                        <input type="checkbox" name="all-query-group" checked="checked" id="all-query-group">
                      </div>
                    </div>
                  </li>
                @foreach ($filters['query_groups'] as $queryGroup)
                <li class="list-group-item query-group--list">
                <div class="d-flex w-100 justify-content-between">
                  <span>@php echo str_repeat('&nbsp;', $queryGroup['level']); @endphp {{ $queryGroup['name'] }}</span>
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="[query_group][{{ $queryGroup['id'] }}]" data-query-group-id="{{ $queryGroup['id'] }}" checked="checked">
                  </div>
                </div>
              </li>
                @endforeach
            </ul>

            <hr/>
            <label for="filter_query_group_id">{{ __('Search Engines') }}: </label>
            <ul class="list-group yandex-setup list-group-sm" data-search-list="yandex">
              <li class="list-group-item bg-info list-group-item-main">
                <div class="d-flex w-100 justify-content-between">
                  <span>{{ __('Yandex') }}  <i id="angledown2" class="fas fa-angle-down"></i><i id="angleright2" class="fas fa-angle-right d-none"></i></span>
                  <div class="custom-control custom-switch yandex-switch">
                    <button type="button" class="btn btn-sm btn-primary add-region--button">{{ __('+ Region') }}</button>
                    <input type="checkbox" name="yandex-search" class="yandex-search" checked="checked">
                  </div>
                </div>
              </li>

              @foreach ($group_regions as $filterRegion)
              <li class="list-group-item region--list">
                <div class="d-flex w-100 justify-content-between">
                  <span>{{ $filterRegion->name }}</span>
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="[yandex][{{ $filterRegion->id }}]" data-region-id="{{ $filterRegion->id }}" checked="checked">
                  </div>
                </div>
              </li>
              @endforeach
            </ul>

            <hr/>

            <ul class="list-group google-setup list-group-sm" data-search-list="google">
              <li class="list-group-item bg-info list-group-item-main">
                <div class="d-flex w-100 justify-content-between">
                  <span>{{ __('Google') }}  <i id="angledown3" class="fas fa-angle-down"></i><i id="angleright3" class="fas fa-angle-right d-none"></i></span>
                  <div class="custom-control custom-switch">
                    <button type="button" class="btn btn-sm btn-primary add-region--button">{{ __('+ Region') }}</button>
                    <input type="checkbox" name="google-search" class="google-search" checked="checked">
                  </div>
                </div>
              </li>

              @foreach ($group_regions as $filterRegion)
                <li class="list-group-item region--list">
                <div class="d-flex w-100 justify-content-between">
                  <span>{{ $filterRegion->name }}</span>
                  <div class="custom-control custom-switch google-switch">
                    <input type="checkbox" name="[google][{{ $filterRegion->id }}]" data-region-id="{{ $filterRegion->id }}" checked="checked">
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
        </div>
    </div>
    <div class="modal-footer">
	    <button type="button" class="btn btn-primary refresh-all-position--button">{{ __('Refresh all') }}</button>
	    <button type="button" class="btn btn-primary refresh-position--button">{{ __('Refresh by filter') }}</button>
	    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
	</div>
</div>

