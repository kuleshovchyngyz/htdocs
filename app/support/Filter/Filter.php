<?php

namespace App\support\Filter;

use App\Competitor;
use App\Position;
use App\Project;
use App\ProjectRegion;
use App\QueryGroup;
use App\Region;
use App\support\Position\Positions;
use App\support\Statistics\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Filter
{
    private $project;
    private $request;
    public $regions;
    public $query_ids;
    public $filterQueryGroupID;
    public $groupQueries;
    public $searchFilter;
    public $startTime;
    public $endTime;
    public $groupQueryIDs;
    public $filterRegions;
    public $queries;
    public $regionID;
    public $query_group_ids;
    public $competitordates;
    public $dates;
    public $projectregions;
    public $PositionObject;
    public $statistics;
    public $positions;
    public $projectCompetitors;
    public $filter_search_engine;
    public function __construct(Project $project, Request $request)
    {
        $this->project = $project;
        $this->request = $request;
    }

    public function set_startTime()
    {
        $monthAgo = Carbon::now()->startOfDay()
            ->subMonths(1);
        $this->startTime = (isset($this->request->filter_start_date) && $this->request->filter_start_date != '') ? Carbon::createFromFormat('d.m.Y', $this->request->filter_start_date)
            ->startOfDay() : $monthAgo;
    }

    public function set_endTime()
    {
        $now = Carbon::now()->endOfDay();
        $this->endTime = (isset($this->request->filter_end_date) && $this->request->filter_end_date != '') ? Carbon::createFromFormat('d.m.Y', $this->request->filter_end_date)
            ->endOfDay() : $now;
    }
    public function   set_filterQueryGroupID()
    {
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')
                ->with('error_message', [__('Please select a Project')]);
        }
        $this->set_startTime();
        $this->set_endTime();

        $this->filterQueryGroupID = 0;
        if (isset($this->request->filter_query_group_id) && $this->request->filter_query_group_id != '') {
            $this->filterQueryGroupID = $this->request->filter_query_group_id;
        }
    }
    public function set_groupQueries()
    {
        $this->set_filterQueryGroupID();
        $this->searchFilter[] = ['positions.project_id', $this->project->id];

        if (isset($this->request->filter_search_engine) && $this->request->filter_search_engine != 'all') {
            $this->filter_search_engine = $this->request->filter_search_engine;
            $this->searchFilter[] = $this->request->filter_search_engine == 'yandex' ? ['positions.yandex_position', '<>', ''] : ['positions.google_position', '<>', ''];
        } else {
            $this->filter_search_engine = 'yandex';
            $this->searchFilter[] = ['positions.yandex_position', '<>', ''];
        }
        $this->groupQueries = QueryGroup::where([['project_id', $this->project->id], ['is_active', 1]])
            ->orderBy('parent_group_id', 'asc')
            ->get();

        if (count($this->groupQueries) < 1) {
            return redirect()->route('query-group.index')
                ->with('error_message', [__('Please create and assign query group to project')]);
        }
    }
    public function set_groupQueryIDs()
    {
        $this->set_groupQueries();
        $this->groupQueryIDs = array_column(QueryGroup::getAllChildren($this->groupQueries, $this->filterQueryGroupID), 'id');
    }
    public function set_groupRegions()
    {
        $this->set_groupQueryIDs();
        $this->groupQueries = QueryGroup::getAllChildren($this->groupQueries, 0);
        //region list
        $groupRegions = DB::table('regions')->select(DB::raw("DISTINCT regions.name as name, regions.id as id"))
            ->join('queries', 'regions.id', '=', 'queries.region_id')
            ->join('query_groups', 'query_groups.id', '=', 'queries.query_group_id')
            ->where([['query_groups.project_id', $this->project
                ->id]])
            ->whereIn('query_groups.id', $this->groupQueryIDs)->get();
        $this->regions = $groupRegions;
    }
    public function set_filterRegions()
    {
        $this->set_startTime();
        $this->set_endTime();
        $this->set_groupRegions();
        $startTime = $this->startTime;
        $endTime = $this->endTime;
        $this->filterRegions = DB::table('regions')->select(DB::raw("regions.name as name, regions.id as id"))
            ->join('positions', 'regions.id', '=', 'positions.region_id')
            ->where($this->searchFilter)->where(function ($q) use ($startTime) {
                $q->where('yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('google_date', '>=', $startTime->format('Y-m-d'));
            })->where(function ($q) use ($endTime) {
                $q->where('yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('google_date', '<=', $endTime->format('Y-m-d'));
            })
            ->groupBy('regions.name')
            ->orderBy('regions.name', 'asc')
            ->get();
    }
    public function set_queries()
    {
        $this->set_filterRegions();
        $this->regionID = '';
        if (isset($this->request->filter_region_id) && $this->request->filter_region_id != '') {
            $this->searchFilter[] = ['regions.id', $this->request->filter_region_id];
            $this->regionID = $this->request->filter_region_id;
        } else if (isset($this->filterRegions[0])) {
            $this->searchFilter[] = ['regions.id', $this->filterRegions[0]->id];
            $this->regionID = $this->filterRegions[0]->id;
        }
        $regionID = $this->regionID;
        $this->queries = DB::table('queries')->select(DB::raw("queries.name as query_name, queries.id as query_id, queries.query_group_id as query_group_id"))
            ->whereIn('queries.query_group_id', $this->groupQueryIDs)->where(function ($q) use ($regionID) {
                $q->where('region_id', $regionID)->orWhere('region_id', '0')
                    ->orWhere('region_id', NULL);
            })

            ->where('is_active', 1)
            ->orderBy('queries.name', 'asc')
            ->get();
    }
    public function get_query_ids($regionID)
    {
        $queries = DB::table('queries')->select(DB::raw("queries.name as query_name, queries.id as query_id, queries.query_group_id as query_group_id"))
            ->whereIn('queries.query_group_id', $this->groupQueryIDs)->where(function ($q) use ($regionID) {
                $q->where('region_id', $regionID)->orWhere('region_id', '0')
                    ->orWhere('region_id', NULL);
            })

            ->where('is_active', 1)
            ->orderBy('queries.name', 'asc')
            ->get();
        return $queries->pluck('query_id')
            ->toArray();
    }
    public function set_query_group_ids()
    {
        $this->set_queries();
        $this->query_group_ids = $this->queries->pluck('query_group_id')
            ->toArray();
        $this->query_ids = $this->queries->pluck('query_id')
            ->toArray();

        $this->query_group_ids = QueryGroup::whereIn('id', $this->query_group_ids)->get()
            ->toArray();
    }
    public function set_competitordates()
    {
        $this->set_query_group_ids();
        $c_searchFilter = $this->searchFilter;
        foreach ($c_searchFilter as  $key1 => $fils) {
            foreach ($fils as $key => $fil) {
                if (str_contains($fil, 'positions.')) {
                    $fils[$key] = str_replace("positions.", "competitor_positions.", $fil);
                }
            }
            $c_searchFilter[$key1] = $fils;
        }
        $startTime = $this->startTime;
        $endTime = $this->endTime;
        $sqlQuery1 = DB::table('competitor_positions')->select(DB::raw("IF(yandex_date IS NOT NULL, yandex_date, google_date) as action_date"))
            ->join('queries', 'queries.id', '=', 'competitor_positions.query_id')
            ->join('regions', 'regions.id', '=', 'competitor_positions.region_id')
            ->where($c_searchFilter)->where(function ($q) use ($startTime) {
                $q->where('yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('google_date', '>=', $startTime->format('Y-m-d'));
            })->where(function ($q) use ($endTime) {
                $q->where('yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('google_date', '<=', $endTime->format('Y-m-d'));
            });

        $this->competitordates = $sqlQuery1->groupBy('action_date')
            ->orderBy('action_date', 'desc')
            ->get();
    }
    public function set_dates()
    {
        $this->set_competitordates();
        $startTime = $this->startTime;
        $endTime = $this->endTime;
        $sqlQuery = DB::table('positions')->select(DB::raw("IF(yandex_date IS NOT NULL, yandex_date, google_date) as action_date"))
            ->join('queries', 'queries.id', '=', 'positions.query_id')
            ->join('regions', 'regions.id', '=', 'positions.region_id')
            ->where($this->searchFilter)->where(function ($q) use ($startTime) {
                $q->where('yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('google_date', '>=', $startTime->format('Y-m-d'));
            })->where(function ($q) use ($endTime) {
                $q->where('yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('google_date', '<=', $endTime->format('Y-m-d'));
            });

        if ($this->filterQueryGroupID > 0) {
            $sqlQuery->whereIn('queries.query_group_id', $this->groupQueryIDs);
        }
        $this->dates = $sqlQuery->groupBy('action_date')
            ->orderBy('action_date', 'desc')
            ->get();
    }
    public function set_projectregions()
    {
        $this->set_dates();
        $startTime = $this->startTime;
        $endTime = $this->endTime;
        $sqlQuery = DB::table('positions')->select(DB::raw("IF(positions.yandex_date IS NOT NULL, positions.yandex_date, positions.google_date) as action_date, regions.name as region_name , competitor_positions.yandex_position as y_p ,competitor_positions.google_position as g_p ,competitor_positions.competitor_id as competitor_id , positions.* "))
            //$sqlQuery = DB::table('positions')->select(DB::raw("IF(positions.yandex_date IS NOT NULL, positions.yandex_date, positions.google_date) as action_date, regions.name as region_name , positions.* "))
            ->join('queries', 'queries.id', '=', 'positions.query_id')
            ->join('regions', 'regions.id', '=', 'positions.region_id')
            ->leftJoin('competitor_positions', 'competitor_positions.position_id', '=', 'positions.id')
            ->where($this->searchFilter)->where(function ($q) use ($startTime) {
                $q->where('positions.yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('positions.google_date', '>=', $startTime->format('Y-m-d'));
            })->where(function ($q) use ($endTime) {
                $q->where('positions.yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('positions.google_date', '<=', $endTime->format('Y-m-d'));
            });

        if ($this->filterQueryGroupID > 0) {
            $sqlQuery->whereIn('queries.query_group_id', $this->groupQueryIDs);
        }

        $this->positions = $sqlQuery->orderBy('action_date', 'desc');
        $this->positions = $sqlQuery->orderBy('id', 'ASC')->get();
        $this->projectregions = ProjectRegion::with('region:id,name')->where('project_id', session('selected_project_id'))
            ->where('region_id', $this->regionID)->get();
    }
    public function set_PositionObject()
    {
        $this->set_projectregions();
        $this->PositionObject = new Positions($this->positions, $this->projectregions, $this->query_group_ids, $this->queries, $this->filter_search_engine);

        if (isset($this->request->sort_date)) {
            $this->PositionObject->sort_date = $this->request->sort_date;
            $this->PositionObject->sort_type = $this->request->sort_type;
            $this->PositionObject->filter_init = $this->request->filter_init;
            $this->PositionObject->filter_competitor = $this->request->competitor;
        }
    }
    public function set_statistics()
    {
        //dump($this->startTime);
        //dump( $this->endTime);
        //        StatisticService($startTime, $endTime, $search_engine, $region_id)
        $this->statistics = '';

        if (sizeof($this->request->all()) != 0) {
            if (count($this->filterRegions) != 0 && count($this->query_ids) != 0) {
                $Statistics = new StatisticService($this->startTime, $this->endTime, $this->request->filter_search_engine,  $this->project->id, $this->request->filter_region_id, $this->query_ids);
                $this->statistics = $Statistics->statistic_query();
            }
        } else {

            if (isset($this->filterRegions[0])) {
                $Statistics = new StatisticService($this->startTime, $this->endTime, 'yandex',  $this->project->id, $this->filterRegions[0]->id, $this->query_ids);
                $this->statistics = $Statistics->statistic_query();
            } else {
                $Statistics = new StatisticService($this->startTime, $this->endTime, 'yandex', 0, $this->project->id);
                $this->statistics = $Statistics->statistic_query();
            }
        }
    }


    public function filter()
    {
        $this->set_PositionObject();
        $this->set_statistics();
        $this->projectCompetitors = Competitor::where('project_id', session('selected_project_id'))->get();

        $alldata = [
            'regions' => $this->projectregions,
            'project' => $this->project,
            'group_regions' => $this->regions,
            'dates' => $this->dates,
            'competitordates' => $this->competitordates,
            'qgis' => $this->query_group_ids,
            'qis' => $this->query_ids,
            'positions' => $this->PositionObject->split(),
            'positionsex' => $this->PositionObject->ex(),
            'position_obj' => $this->PositionObject,
            'sort_type' => $this->PositionObject->sort_type,
            'sort_date' => $this->PositionObject->sort_date,
            'sort_color' => $this->PositionObject->sort_color,
            'queries' => $this->PositionObject->queries,
            'competitors' => $this->projectCompetitors,
            'region_id' => $this->regionID,
            'statistics' => $this->statistics,
            'method' => $this->filter_search_engine,
            'filters' => [
                'query_groups' => $this->groupQueries,
                'regions' => $this->filterRegions,
                'values' => [
                    'start_date' => $this->startTime->format('d.m.Y'),
                    'end_date' => $this->endTime->format('d.m.Y'),
                    'query_group_id' => $this->request->filter_query_group_id,
                    'search_engine' => $this->request->filter_search_engine,
                    'region_id' => $this->request->filter_region_id,
                ]
            ]
        ];

        $c = $alldata;
        $alldata['c'] = $c;

        return $alldata;
    }
    public function first_last_position_date($search_engine='yandex')
    {

        $p = Position::where('method', $search_engine)
            ->where($search_engine . '_date', '>=', (gettype($this->startTime) === 'object') ? $this->startTime->format('Y-m-d') : $this->startTime)
            ->where($search_engine . '_date', '<=', (gettype($this->endTime) === 'object') ? $this->endTime->format('Y-m-d') : $this->endTime)
//            ->whereIn("query_id", $this->query_ids)

            ->where('project_id', $this->project->id)
            ->select($search_engine . '_date')->distinct()
            ->orderby($search_engine . '_date', 'ASC')->get()
            ->pluck($search_engine . '_date');

        if (count($p) > 1) {
            return [$p[0] ,$p[count($p) - 1]];
        } else {
            return null;
        }

    }
}
