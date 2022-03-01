<?php


namespace App\support\Statistics;


use App\Position;
use App\QueryGroup;
use App\Query;

use Illuminate\Support\Facades\DB;

class StatisticService
{
    public $firstDate = '';
    public $lastDate = '';
    public $startTime;
    public $endTime;
    public  $search_engine;

    public  $query_ids;
    public $positions_last;
    public $positions_first;

    public $statistics;
    public $positions;
    public static $arr = [[1, 10], [11, 30], [31, 50], [51, 150], [1, 3]];
    public $stop = true;
    public $project;
    public $region_id;
    public $nodates;
    public function __construct($startTime, $endTime, $search_engine, $project, $region_id = null, $query_ids = [])
    {



        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->region_id =  $region_id;
        $this->project = $project;
        $this->search_engine = $search_engine;
        $this->query_ids = $query_ids;
        $this->search_engine = ($this->search_engine == null) ? 'yandex' : $this->search_engine;

        $this->first_last_position_date();

        if ($this->firstDate == 0) {
            $this->statistics = null;
        } else {

            $this->test($this->project, $this->search_engine, $this->region_id, [$this->firstDate, $this->lastDate]);
        }
    }

    /**
     * @param mixed $nodates
     */
    public function setNodates($nodates): void
    {
        $this->nodates = $nodates;
    }

    /**
     * @return mixed
     */
    public function getNodates()
    {
        return $this->nodates;
    }
    public function test($project_id = 18, $method = 'yandex', $region_id = 16, $dates = ["2022-01-04", "2022-01-25"])
    {
        // REmind Askar about фinactive queries
        $intervals = [[1, 10], [11, 30], [31, 50], [51, 150], [1, 3]];
        $rates = [];
        $tops = [];
        $quantities = [];
        $method_position = $method . "_position";

        $qg = QueryGroup::where('project_id', $project_id)->pluck('id');
        $number_of_queries = Query::whereIn('query_group_id', $qg)
            ->where('is_active', 1)
            ->count();
        $positions = Position::where("project_id", $project_id)->where("method", $method)
            ->where("region_id", ($region_id === null) ? 'not REGEXP' : '=', $region_id ?? '[+]') //If you want only one region
            ->whereIn($method . "_date", $dates)->select("region_id", $method_position, $method . "_date", "query_id")
            ->get()
            ->groupBy(["query_id", "region_id"])
            ->map(function ($value, $key) use ($method, &$quantities) {
                return $value->each(function ($item) use ($method, &$quantities, $key) {
                    if ($item->count() == 2) {
                        $quantities[]['diff'] = $item->first()[$method . '_position'] - $item->last()[$method . '_position'];

                        $index = count($quantities) - 1;
                        $quantities[$index][$item->first()[$method . '_date']] = $item->first()[$method . '_position'];
                        $quantities[$index][$item->last()[$method . '_date']] = $item->last()[$method . '_position'];
                        $quantities[$index]['query_id'] = $key;
                    } else if ($item->count() == 1) {
                        $quantities[][$item->first()[$method . '_date']] = $item->first()[$method . '_position'];
                        $index = count($quantities) - 1;
                        $quantities[$index]['query_id'] = $key;
                    }
                });
            });
        $rates = [
            'decrease' => 0,
            'increase' => 0,
            'not_changed' => 0
        ];

        // dump($positions->first());
        //dump($positions);
        //dd($positions->first()->toArray());

        $rates = collect($quantities)->countBy(function ($item) use ($intervals, $dates, &$tops) {

            collect($intervals)->map(function ($value) use ($item, $dates, &$tops) {
                if (isset($item[$dates[0]]) && ($item[$dates[0]] >= $value[0] && $item[$dates[0]] <= $value[1])) {

                    $tops[$dates[0] . ' ' . $value[0] . '-' . $value[1]] = $tops[$dates[0] . ' ' . $value[0] . '-' . $value[1]] ?? 0;
                    $tops[$dates[0] . ' ' . $value[0] . '-' . $value[1]] += 1;
                }
                if (isset($item[$dates[1]]) && ($item[$dates[1]] >= $value[0] && $item[$dates[1]] <= $value[1])) {

                    $tops[$dates[1] . ' ' . $value[0] . '-' . $value[1]] = $tops[$dates[1] . ' ' . $value[0] . '-' . $value[1]] ?? 0;
                    $tops[$dates[1] . ' ' . $value[0] . '-' . $value[1]] += 1;
                }
            });

            if (isset($item['diff']) && $item['diff'] > 0) {
                return "increase";
            }
            if (isset($item['diff']) && $item['diff'] == 0) {
                return "not_changed";
            }
            if (isset($item['diff']) && $item['diff'] < 0) {
                return "decrease";
            }
        })->all();

        $rates['decrease'] = $rates['decrease'] ?? 0;
        $rates['increase'] = $rates['increase'] ?? 0;
        $rates['not_changed'] = $rates['not_changed'] ?? 0;
        if (!isset($rates["decrease"])) {
            dump($this);
            dd($rates);
        }
        $this->statistics =  new Statistics(
            $rates['decrease'] ?? 0,
            $rates["increase"] ?? 0,
            $rates["not_changed"] ?? 0,
            round(collect($quantities)->avg($dates[1])),
            round(collect($quantities)->avg($dates[0])) - round(collect($quantities)->avg($dates[1]))
        );
        $total = ($rates["increase"] + $rates["not_changed"] + $rates["decrease"]) == 0 ? 1 : ($rates["increase"] + $rates["not_changed"] + $rates["decrease"]);
        $this->statistics->set_percents(
            $rates["decrease"] * 100 / $total,
            $rates["increase"] * 100 / $total,
            $rates["not_changed"] * 100 / $total
        );


        //dump($tops[$dates[1] . ' 1-10']);
        $this->statistics->set_1_10($tops[$dates[1] . ' 1-10'] ?? 0);
        $this->statistics->set_11_30($tops[$dates[1] . ' 11-30'] ?? 0);
        $this->statistics->set_31_50($tops[$dates[1] . ' 31-50'] ?? 0);
        $this->statistics->set_51_100($tops[$dates[1] . ' 51-150'] ?? 0);
        $this->statistics->set_1_3($tops[$dates[1] . ' 1-3'] ?? 0);

        $this->statistics->set_total($number_of_queries);
        $this->statistics->set_range_percents(
            round($this->statistics->total_1_10 * 100 / $this->statistics->total),
            round($this->statistics->total_11_30 * 100 / $this->statistics->total),
            round($this->statistics->total_31_50 * 100 / $this->statistics->total),
            round($this->statistics->total_51_100 * 100 / $this->statistics->total),
            round($this->statistics->total_1_3 * 100 / $this->statistics->total)
        );

        $this->statistics->set_range_first(
            $tops[$dates[0] . ' 1-10'] ?? 0,
            $tops[$dates[0] . ' 11-30'] ?? 0,
            $tops[$dates[0] . ' 31-50'] ?? 0,
            $tops[$dates[0] . ' 51-150'] ?? 0,
            $tops[$dates[0] . ' 1-3'] ?? 0

        );
        // dump($this->statistics);
        // $avg['avg ' . $dates[0]] = round(collect($quantities)->avg($dates[0]));
        // $avg['avg ' . $dates[1]] = round(collect($quantities)->avg($dates[1]));
        // dump($avg);
        // dump($number_of_queries);
        // dump($tops);
        // dd($rates);
    }

    public function statistic_query()
    {
        return $this->statistics;
    }


    public function first_last_position_date()
    {

        $p = Position::where('method', $this->search_engine)
            ->where("region_id", ($this->region_id === null) ? 'not REGEXP' : '=', $this->region_id ?? '[+]')
            ->where($this->search_engine . '_date', '>=', (gettype($this->startTime) === 'object') ? $this->startTime->format('Y-m-d') : $this->startTime)
            ->where($this->search_engine . '_date', '<=', (gettype($this->endTime) === 'object') ? $this->endTime->format('Y-m-d') : $this->endTime)
            ->where('project_id', $this->project)
            ->select($this->search_engine . '_date')
            ->distinct()
            ->orderby($this->search_engine . '_date', 'ASC');


        $p = $this->query_ids===[] ? $p->pluck($this->search_engine . '_date') :
            $p->whereIn("query_id", $this->query_ids)
            ->pluck($this->search_engine . '_date');

        if (count($p) > 1) {
            $this->firstDate = $p[0];
            $this->lastDate = $p[count($p) - 1];
        } else {
            $this->firstDate = 0;
            $this->lastDate = 0;
        }

    }
}
