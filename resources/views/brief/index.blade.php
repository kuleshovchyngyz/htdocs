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
    <div class="w-100 content-wrapper container summary__charts">
        <div class="row">
            <div class="col-lg-12 mt-3">
                <div class="d-flex summary__charts--setting">
                    <a href="#" data-toggle="modal" data-target="#summary-add-new-widget">
                        <img src="{{ asset('/public/images/summary/add.svg') }}" alt="">
                    </a>
                    <a href="#">
                        <img src="{{ asset('/public/images/summary/export.svg') }}" alt="">
                    </a>
                </div>
            </div>

            {{-- Графики --}}
            <div class="col-lg-8 mt-3">
                @foreach ($charts->where('summary_result','!=','side_summary')->get() as $key => $chart)

                    <div class="summary__charts--block bg-white mt-4">
                        <div class="d-flex justify-content-between">
                            <div class="summary__charts--title">

                                {{ $chart->summary_search_engine }} , {{ $chart->region->pluck('name')->implode(',') }},
                                {{ $chart->summary_type_widget }},
                                с {{ $chart->summary_start_date }} по {{ $chart->summary_end_date }}
                            </div>
                            <div class="summary__charts--setting">
                                <a href="#" data-toggle="modal" data-target="#summary-add-edit-widget">
                                    <img src="{{ asset('/public/images/summary/edit.svg') }}" alt="">
                                </a>
                                <a href="#" data-toggle="modal" data-target="#summary-add-delete-widget">
                                    <img src="{{ asset('/public/images/summary/delete.svg') }}" alt="">
                                </a>
                            </div>
                        </div>

                        <div id="chart{{ $key }}" class="summary__chart-block"
                            data-date="{{ $chart->summary_date_get }}"
                            data-middle-position="{{ $chart->summary_result }}"
                            data-type-widget="{{ $chart->summary_type_widget }}"> </div>
                    </div>
                @endforeach
                <div class="row">
                    <div class="col-lg-6 mt-4">
                        <div class="summary__charts--radialbar bg-white">
                            <div class="d-flex justify-content-between">
                                <div class="summary__charts--title">
                                    Яндекс, Нижний Новгород
                                </div>
                                <div class="summary__charts--setting">
                                    <a href="#">
                                        <img src="{{ asset('/public/images/summary/edit.svg') }}" alt="">
                                    </a>
                                    <a href="#">
                                        <img src="{{ asset('/public/images/summary/delete.svg') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-9 col-xl-12"> </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- ------- --}}
            {{-- Регионы и план проверок --}}
            <div class="col-lg-4 mt-3">
                <div class="summary__charts--region bg-white">
                    <div class="d-flex justify-content-between align-items-center">
{{--                        <div class="topTabs">Регионы, Топ 3/ 10 /30/50/100</div>--}}
                            <div class="topTabs">
                            <span>Регионы, Топ</span>
{{--                            $tops = ['total_1_3'=>'total_first_1_3',''=>'total_first_1_10',''=>'total_first_11_30',''=>'total_first_31_50',''=>'total_first_51_100'];--}}
                            <span class="total_1_3 active"> 3/</span>
                            <span class="total_1_10">10/</span>
                            <span class="total_11_30">30/</span>
                            <span class="total_31_50">50/</span>
                            <span class="total_51_100">100</span>
                        </div>
                        <a href="#">
                            <img src="{{ asset('/public/images/summary/back.svg') }}" alt="">
                        </a>
                    </div>
                    <div class="summary__charts--region-title">
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-3">Яндекс</div>
                            <div class="col-lg-3">Google</div>
                        </div>
                    </div>


                    <div class="summary__charts--region-content">
                        @foreach($regions_array as $key=>$item)
                            @foreach($tops as $keyTop=>$top)
                            <div class="row {{ $keyTop }} @if($keyTop!='total_1_3') d-none @endif">
                                <div class="col-lg-6">
                                    <div class="summary__charts--region-content-name">{{ $item }}
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex">

                                            {{ $stats[$key]['yandex']->statistics->$keyTop }}

                                            @if(($stats[$key]['yandex']->statistics->$keyTop - $stats[$key]['yandex']->statistics->$top) >0)
                                                <div class="select__project--table-rating-up">{{ $stats[$key]['yandex']->statistics->$keyTop - $stats[$key]['yandex']->statistics->$top }}</div>
                                            @elseif(($stats[$key]['yandex']->statistics->$keyTop - $stats[$key]['yandex']->statistics->$top) <0)
                                                <div class="select__project--table-rating-down">{{ abs($stats[$key]['yandex']->statistics->$keyTop - $stats[$key]['yandex']->statistics->$top) }}</div>
                                            @endif


                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex">
                                        {{ $stats[$key]['google']->statistics->$keyTop }}

                                        @if(($stats[$key]['google']->statistics->$keyTop - $stats[$key]['google']->statistics->$top) >0)
                                            <div class="select__project--table-rating-up">{{ $stats[$key]['google']->statistics->$keyTop - $stats[$key]['google']->statistics->$top }}</div>
                                        @elseif(($stats[$key]['google']->statistics->$keyTop - $stats[$key]['google']->statistics->$top) <0)
                                            <div class="select__project--table-rating-down">{{ abs($stats[$key]['google']->statistics->$keyTop - $stats[$key]['google']->statistics->$top) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endforeach





                    </div>
                </div>
                <div class="summary__charts--plan bg-white mt-3">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>Планы проверок</div>
                        <a href="#">
                            <img src="{{ asset('/public/images/summary/back.svg') }}" alt="">
                        </a>
                    </div>
                    @foreach($schedules as $schedule)
                        @if($schedule->type=='weekly')
                            <div class="row summary__charts--plan-content">
                                <div class="col-lg-12">
                                    <div class="summary__charts--plan-content-title">
                                        {{ $schedule->name }}
                                    </div>
                                    <div class="summary__charts--plan-content-near">Ближайший съём через 2 дня</div>

                                    <div class="summary__charts--plan-content-days d-flex justify-content-between">
                                        @for($i=1;$i<=7;$i++)
                                            @if (in_array($i, explode(',',$schedule->date)))
                                                {!! "<span class='current__day'>$weekdays[$i]</span>" !!}
                                            @else
                                                {{ $weekdays[$i] }}
                                            @endif
                                        @endfor
                                        <div class="summary__charts--plan-content-hour">
                                            в {{ $schedule->time }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($schedule->type=='monthly')
                            <div class="row summary__charts--plan-content">
                                <div class="col-lg-12">
                                    <div class="summary__charts--plan-content-title">
                                        {{ $schedule->name }}
                                    </div>
                                    <div class="summary__charts--plan-content-near">Ближайший съём через 2 дня</div>

                                    <div class="summary__charts--plan-content-days d-flex justify-content-between">
                                        @foreach(explode(',',$schedule->date) as $key=>$value)

                                            @if($key<count(explode(',',$schedule->date))-1)
                                                {{ $value }}
                                            @else
                                                {{ "и ".$value." числа" }}
                                            @endif
                                            @if($key<count(explode(',',$schedule->date))-2)
                                                {{ ', ' }}
                                            @endif

                                        @endforeach
                                        <div class="summary__charts--plan-content-hour">
                                            в {{ $schedule->time }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
            </div>
            {{-- ------- --}}
        </div>
    </div>
@section('modal-section')
    @include('brief.modal.modal-content')
@endsection

<script>
    let options = [];
    let chartSummary = [];
    $('.summary__chart-block').each(function(index) {
        let chartId = $(this).attr('id');
        let chartDate = $(this).data('date');
        let chartMiddlePosition = $(this).data('middle-position');
        let chartTypeWidget = $(this).data('type-widget');
        options[index] = {
            chart: {
                type: 'line',
                height: 200,
                width: '100%',
                toolbar: {
                    autoSelected: 'pan',
                    show: false
                }
            },
            series: [{
                name: chartTypeWidget,
                data: chartMiddlePosition.split(',')
            }],
            colors: ['#FEB700'],
            stroke: {
                width: 2,
                curve: 'smooth'
            },

            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 6,
                colors: '#fff',
                strokeColors: '#FEB700',
                strokeWidth: 3,
            },
            xaxis: {
                categories: chartDate.split(','),
            },


        };

        chartSummary[index] = new ApexCharts(document.querySelector(`#${chartId}`), options[index]);

        chartSummary[index].render();
    })
</script>

@endsection
