<?php
///dump(max_by_date(object_to_array($competitordates,'action_date')));
?>
@foreach ($competitors as $competitor)
    @if ($competitor->hasregion($region_id))
        <?php
        // dump($competitor);
        ?>
        <table class="table  table-sm d-none" id="positions_table_{{ $competitor->id }}">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">{{ __('Queries') }} ({{ $queries->count() }})</th>

                    @foreach ($dates as $date)
                        <th scope="col"><a href="#" data-clicked="0"
                                data-sort="{{ \Carbon\Carbon::parse($date->action_date)->format('Y-m-d') }}"
                                class="sort_date" @if (\Carbon\Carbon::parse($date->action_date)->format('Y-m-d') == $sort_date)
                                style="color: {{ $sort_color }}"
                    @endif
                    >{{ \Carbon\Carbon::parse($date->action_date)->format('d.m.Y') }}</a></th>
    @endforeach
    </tr>
    </thead>
    <tbody>
        <?php
        //dump($regions->toArray());
        //dump($filters['query_groups']);
        //dump($queries->toArray());
        //dump($qgis);
        //var_dump($po);
        ?>
        @foreach ($queries as $query)
            <tr data-query-id="{{ $query->query_id }}" class="">
                <td>{{ $query->query_name }} </td>

                @foreach ($competitordates as $date)

                    @php($positionKey = $query->query_id . '--' . $date->action_date)
                    <td>

                        @if (isset($positions[$positionKey]))
                            @if (isset($positions[$positionKey]['yandex']))
                                @foreach ($positions[$positionKey]['yandex'] as $yandex)
                                    <?php
                                    //dd($yandex);
                                    $s = $yandex->position_mm[$competitor->id];
                                    //dump($s);
                                    ?>
                                    @if (!empty($s))
                                        <div class="yandex-position-container {{ getPositionColorClass($s['y_p']) }}">

                                            @if ($s['y_p'] > 0 && $s['y_p'] < 100)

                                                {{ $s['y_p'] }}
                                            @else
                                                {{ '--' }}
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            @if (isset($positions[$positionKey]['google']))
                                @foreach ($positions[$positionKey]['google'] as $google)
                                    <?php
                                    
                                    $s = $google->position_mm[$competitor->id];
                                    //dump($google);
                                    ?>
                                    @if (!empty($s))
                                        <div
                                            class="google-position-container  {{ getPositionColorClass($s['g_p']) }}">
                                            @if ($s['g_p'] > 0 && $s['g_p'] < 100)
                                                {{-- {{$google->position}} --}}
                                                {{ $s['g_p'] }}
                                            @else
                                                {{ '--' }}
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </td>

                @endforeach
            </tr>
        @endforeach
    </tbody>
    </table>

@endif
@endforeach


<table class="table  table-sm d-none" id="positions_table_compare">
    <thead class="thead-dark">
        <tr class="h-25">
            <th scope="col">{{ __('Queries') }} ({{ $queries->count() }})</th>
            <th class="date_header_url">
                <div class="d-block">
                    <span class="mk">{{ remove_slashes_w($project->projectsubdomain($region_id)) }}</span>
                    <span>{{ \Carbon\Carbon::parse(max_by_date(object_to_array($competitordates, 'action_date')))->format('d.m.Y') }}</span>
                </div>
            </th>
            @foreach ($competitors as $competitor)
                @if ($competitor->hasregion($region_id))

                    <th scope="col" class="date_header_url">

                        <div class="d-block">
                            <span class="mk">{{ remove_slashes_w($competitor->url) }}</span>
                            <span>{{ \Carbon\Carbon::parse(max_by_date(object_to_array($competitordates, 'action_date')))->format('d.m.Y') }}</span>
                        </div>
                    </th>
                @endif
            @endforeach
        </tr>
    </thead>
    <tbody>

        @foreach ($queries as $query)
            <tr data-query-id="{{ $query->query_id }}" class="">
                <td>{{ $query->query_name }} </td>

                @foreach ($competitordates as $date)
                    @if (max_by_date(object_to_array($competitordates, 'action_date')) === $date->action_date)
                        @php($positionKey = $query->query_id . '--' . $date->action_date)
                        <td>

                            @if (isset($positions[$positionKey]))
                                @if (isset($positions[$positionKey]['yandex']))
                                    @foreach ($positions[$positionKey]['yandex'] as $yandex)
                                        <div class="yandex-position-container {{ $yandex->position_class_name }}">
                                            @if ($yandex->position > 0)
                                                {{ $yandex->position }}
                                                {{-- <i class="fa {{  $yandex->matched_icon  }} text-secondary float-right" title={{ $yandex->full_url }} aria-hidden="true"></i> --}}
                                                <i class="fa {{ $yandex->matched_icon }} text-secondary float-right"
                                                    data-toggle="popover" title={{ $yandex->target_path }}
                                                    aria-hidden="true" data-content={{ $yandex->full_url }}></i>
                                            @else
                                                {{ '--' }}
                                            @endif


                                        </div>
                                    @endforeach
                                @endif
                                @if (isset($positions[$positionKey]['google']))
                                    @foreach ($positions[$positionKey]['google'] as $google)
                                        <div class="google-position-container {{ $google->position_class_name }}">
                                            @if ($google->position > 0)
                                                {{ $google->position }}
                                                {{-- <i class="fa {{  $google->matched_icon  }} text-secondary float-right" title={{ $google->full_url }} aria-hidden="true"></i> --}}
                                                <i class="fa {{ $google->matched_icon }} text-secondary float-right"
                                                    data-toggle="popover" title={{ $google->target_path }}
                                                    aria-hidden="true" data-content={{ $google->full_url }}></i>
                                            @else
                                                {{ '--' }}
                                            @endif
                                        </div>

                                    @endforeach
                                @endif
                            @endif
                        </td>
                        @foreach ($competitors as $competitor)
                            @if ($competitor->hasregion($region_id))
                                @php($positionKey = $query->query_id . '--' . $date->action_date)
                                <td>

                                    @if (isset($positions[$positionKey]))
                                        @if (isset($positions[$positionKey]['yandex']))
                                            @foreach ($positions[$positionKey]['yandex'] as $yandex)
                                                <?php
                                                $s = $yandex->position_mm[$competitor->id];
                                                //dump($s[0]);
                                                ?>
                                                @if (!empty($s))
                                                    <div
                                                        class="yandex-position-container {{ getPositionColorClass($s['y_p']) }}">

                                                        @if ($s['y_p'] > 0 && $s['y_p'] < 100)
                                                            {{ $s['y_p'] }}
                                                        @else
                                                            {{ '--' }}
                                                        @endif


                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if (isset($positions[$positionKey]['google']))
                                            @foreach ($positions[$positionKey]['google'] as $google)
                                                <?php
                                                
                                                $s = $google->position_mm[$competitor->id];
                                                //dump($s[0]);
                                                ?>
                                                @if (!empty($s))
                                                    <div
                                                        class="google-position-container {{ getPositionColorClass($s['g_p']) }}">
                                                        @if ($s['g_p'] > 0 && $s['g_p'] < 100)
                                                            {{-- {{$google->position}} --}}
                                                            {{ $s['g_p'] }}
                                                        @else
                                                            {{ '--' }}
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </td>
                            @endif

                        @endforeach
                    @endif
                @endforeach

                {{-- Competitor Postion Columns --}}


            </tr>
        @endforeach
    </tbody>
</table>
