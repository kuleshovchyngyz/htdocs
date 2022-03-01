<ul class="list-group__nested level-{{ $level }}">
    @if ($level == 0)
        <li class="list-group-item open">
            <div class="title" data-id="0">
                <span>
                    <span class="editable" data-name="vitae">{{ __('All queries') }}</span>
                </span>
            </div>
        </li>
    @endif
    <?php
    
    ?>
    @foreach ($entries as $item)
        <li class="list-group-item open">
            <div class="title {{ $item['is_active'] == '0' ? ' text-muted' : '' }}" data-id="{{ $item['id'] }}">
                <span>
                    @if (isset($item['children']) && count($item['children']) > 0)
                        <span class="icon-group">
                            <i class="fas fa-minus"></i>
                        </span>
                    @endif
                    <span class="editable" data-name="{{ $item['name'] }}">{{ $item['name'] }}</span>
                </span>
                <div class="target_path_{{ $item['id'] }} list-group-item-target text-right">
                    @if (isset($item['target_path']))
                        {{ $item['target_path'] }}
                    @endif
                </div>
            </div>
            @if (isset($item['children']) && count($item['children']) > 0)
                @include ('shared.tree_entry', ['entries' => $item['children'], 'level' => $level+1])
            @endif
        </li>
    @endforeach
</ul>
