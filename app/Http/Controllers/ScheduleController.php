<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Competitor;
use App\Interfaces\ApiInterface;
use App\Jobs\GetPositions;
use App\Jobs\GetTaskids;
use App\Position;
use App\Project;
use App\PendingQuery;
use App\ProjectRegion;
use App\QueryGroup;
use App\ReadyScheduledPosition;
use App\Region;
use App\Schedule;
use App\Scheduled_positions;
use App\Task;
use App\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{

    public function ddd()
    {
        $apiInterface = resolve(ApiInterface::class);
        dd($apiInterface->getUnits());
        //Balance::create(['balance'=>$apiInterface->getUnits()]);
    }
    public function proceed_scheduled_positions_to_get_task_ids($id, $project_id)
    {
        // $apiInterface = resolve(ApiInterface::class);
        // Balance::create(['balance'=>$apiInterface->getUnits()]);
        $shedule = Schedule::find($id);
        //$this->send_to_tg_bot( 'scheduled type:'.$shedule->type.PHP_EOL.'scheduled date:'.$shedule->date.PHP_EOL.'at time:'.$shedule->time,$shedule->project->name );
        $task = $shedule->task;
        $ready_pos = $task->ready_scheduled_position;
        $result = $this->make_ready($ready_pos);
        if ($result) {
            $this->make_schedule($project_id);
        }
    }
    public function make_schedule($project_id = 0)
    {
        $s = Schedule::where('date', 'undefined')->wheretype('get_position')->wherestatus('1')->where('project_id', $project_id)->count();
        if ($s == 0) {
            Schedule::create([
                'type' => 'get_position',
                'date' => 'undefined',
                'time' => '',
                'status' => false,
                'task_id' => '',
                'project_id' => $project_id
            ]);
        } else {
            $s = Schedule::where('date', 'undefined')->wheretype('get_position')->where('project_id', $project_id)->wherestatus('1')->first();
            $s->status = 0;
            $s->save();
            //Schedule::where('date','undefined')->wheretype('get_position')->wherestatus('1')->delete();
        }
    }
    public function get_part_task_id()
    {

        $yandexes = PendingQuery::where('method', 'yandex')->where('failed', '0')->get();

        if ($yandexes->count() > 0) {
            foreach ($yandexes as $yandex) {
                $p = Position::where('yandex_date', 'like', Carbon::now()->format('Y-m-d') . '%')->where('method', 'yandex')->where('region_id', $yandex->region_id)->where('query_id', $yandex->query_id)->where('status', 'complete')->count();
                if ($p > 0) {
                    $yandex->delete();
                } else {
                    GetTaskids::dispatch($yandex->toArray());
                    $yandex->failed = '1';
                    $yandex->save();
                }
            }
            return $yandexes->count();
        } else {
            $googles = PendingQuery::where('method', 'google')->where('failed', '0')->get();
            foreach ($googles as $google) {
                $p = Position::where('google_date', 'like', Carbon::now()->format('Y-m-d') . '%')->where('method', 'google')->where('region_id', $google->region_id)->where('query_id', $google->query_id)->where('status', 'complete')->count();
                if ($p > 0) {
                    $google->delete();
                } else {
                    GetTaskids::dispatch($google->toArray());
                    $google->failed = '1';
                    $google->save();
                }
            }
        }
        return $googles->count();
    }

    public function get_positions_from_errors($date, $project_id)
    {
        $errors = Position::where('created_at', 'like', $date . '%')->where('project_id', $project_id)->where('status', 'error')->select('region_id', 'project_id', 'query_id', 'method', 'created_at')->get();

        $number_of_errors = $errors->count();
        $this->send_to_tg_bot('errors', $number_of_errors);

        foreach ($errors as $error) {
            $pending = PendingQuery::where('method', $error->method)->where('region_id', $error->region_id)->where('project_id', $error->project_id)->where('query_id', $error->query_id)->where('updated_at', 'like', $error->created_at->format('Y-m-d') . '%');
            $pending->update(['failed' => '0']);
        }
        Position::where('status', 'error')->delete();
        if ($number_of_errors > 0) {
            $this->make_schedule();
        }
    }
    public function add_to_get_part_task_id($method, $number)
    {
        $y_n = 500;
        $g_n = 150;
        if ($method == "yandex" && $number > 0) {
            $yandexes = PendingQuery::where('method', 'yandex')->where('failed', '0')->take($y_n - $number)->get();
            foreach ($yandexes as $yandex) {
                GetTaskids::dispatch($yandex->toArray());
                $yandex->failed = '1';
                $yandex->save();
            }
        }
        if ($method == "google" && $number > 0) {
            $googles = PendingQuery::where('method', 'google')->where('failed', '0')->take($g_n - $number)->get();
            foreach ($googles as $google) {
                GetTaskids::dispatch($google->toArray());
                $google->failed = '1';
                $google->save();
            }
        }
    }

    public function proceed_scheduled_positions_from_task_ids($id, $project_id)
    {

        $shedule = Schedule::find($id);
        $pendingPositions = Position::with('project.projectRegions')->where([
            ['positions.status', 'pending'],
            ['positions.project_id', $project_id],
            ['positions.updated_at', '>=', Carbon::yesterday()->startOfDay()]
            //  ['positions.task_table_id','<>', -1]
        ])->get();

        if (count($pendingPositions) > 0) {
            $this->send_to_tg_bot('count', count($pendingPositions));
            $this->send_to_tg_bot('method', $pendingPositions[0]->method);
            //$this->add_to_get_part_task_id($pendingPositions[0]->method,count($pendingPositions));

            foreach (($pendingPositions->chunk(1)) as $key => $p) {
                GetPositions::dispatch($p);
                //$this->send_to_tg_bot($shedule->type,'dispatched');
                $p[$key]->checked = $p[$key]->checked + 1;
                $p[$key]->save();
                if ($p[$key]->checked > 40) {
                    $p[$key]->task_table_id = -1;
                    $p[$key]->save();
                }
            }
        } else {
            $number = $this->get_part_task_id();
            $this->send_to_tg_bot('getting part', $number);
            if ($number == 0) {
                $apiInterface = resolve(ApiInterface::class);
                Balance::create(['balance' => $apiInterface->getUnits()]);
                $shedule->status = 1;
                $shedule->save();
                $this->get_positions_from_errors(Carbon::now()->format('Y-m-d'), $project_id);
                // $this->get_positions_from_errors(Carbon::yesterday()->format('Y-m-d'));
            }
            Position::where('google_position',  -3)->update(['google_position' => '100']);
            Position::where('yandex_position', -3)->update(['yandex_position' => '100']);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $dayOfWeek = \Carbon\Carbon::now()->dayOfWeek;
        $dayOfMonth = (int)(\Carbon\Carbon::now()->format('d'));
        session(['selected_project_id' => $id]);
        $task = Task::where('project_id', session('selected_project_id'))->get();
        $schedule = Schedule::where('project_id', session('selected_project_id'))->where('name','!=',null)->where('date','!=','undefined')->get();
        $weekdays = [1=> 'Пн',2=> 'Вт',3=> 'Ср',4=> 'Чт',5=> 'Пт',6=> 'Сб',7=> 'Вс'];
        return view('project.schedule.index', ["tasks" => $task,"schedules" => $schedule,'weekdays'=>collect($weekdays),'dayOfWeek'=>$dayOfWeek,'dayOfMonth'=>$dayOfMonth]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return "sd";
        // dd('creating');
        //dd($this->filters());
        return view('project.schedule.create', $this->filters());
    }
    public function test()
    {
        return "sd";
    }
    public function filters()
    {
        //$now = Carbon::now('UTC');
        // return $now;
        $project = Project::where('id', session('selected_project_id'))->first();
        //dd($project->id);
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project')]);
        }
        $monthAgo = Carbon::now()->startOfDay()->subMonths(1);
        $now = Carbon::now()->endOfDay();

        $startTime = (isset($request->filter_start_date) && $request->filter_start_date != '')
            ? Carbon::createFromFormat('d.m.Y', $request->filter_start_date)->startOfDay()  : $monthAgo;

        $endTime = (isset($request->filter_end_date) && $request->filter_end_date != '')
            ? Carbon::createFromFormat('d.m.Y', $request->filter_end_date)->endOfDay() : $now;


        $filterQueryGroupID = 0;
        if (isset($request->filter_query_group_id) && $request->filter_query_group_id != '') {
            $filterQueryGroupID = $request->filter_query_group_id;
        }

        $searchFilter[] = ['positions.project_id', $project->id];

        if (isset($request->filter_search_engine) && $request->filter_search_engine != 'all') {
            $searchFilter[] = $request->filter_search_engine == 'yandex' ? ['positions.yandex_position', '<>', ''] : ['positions.google_position', '<>', ''];
        } else {
            $searchFilter[] = ['positions.yandex_position', '<>', ''];
        }
        $groupQueries = QueryGroup::where([
            ['project_id', $project->id],
            ['is_active', 1]
        ])
            ->orderBy('parent_group_id', 'asc')->get();

        if (count($groupQueries) < 1) {
            return redirect()->route('query-group.index')->with('error_message', [__('Please create and assign query group to project')]);
        }

        $groupQueryIDs = array_column(QueryGroup::getAllChildren($groupQueries, $filterQueryGroupID), 'id');

        //query group list
        $groupQueries = QueryGroup::getAllChildren($groupQueries, 0);

        //region list
        $groupRegions = DB::table('regions')
            ->select(DB::raw("DISTINCT regions.name as name, regions.id as id"))
            ->join('queries', 'regions.id', '=', 'queries.region_id')
            ->join('query_groups', 'query_groups.id', '=', 'queries.query_group_id')
            ->where([
                ['query_groups.project_id', $project->id]
            ])
            ->whereIn('query_groups.id', $groupQueryIDs)
            ->get();

        $filterRegions = DB::table('regions')
            ->select(DB::raw("regions.name as name, regions.id as id"))
            ->join('positions', 'regions.id', '=', 'positions.region_id')
            ->where($searchFilter)
            ->where(function ($q) use ($startTime) {
                $q->where('yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('google_date', '>=', $startTime->format('Y-m-d'));
            })
            ->where(function ($q) use ($endTime) {
                $q->where('yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('google_date', '<=', $endTime->format('Y-m-d'));
            })
            ->groupBy('regions.name')
            ->orderBy('regions.name', 'asc')
            ->get();

        $regionID = '';
        if (isset($request->filter_region_id) && $request->filter_region_id != '') {
            $searchFilter[] = ['regions.id', $request->filter_region_id];
            $regionID = $request->filter_region_id;
        } else if (isset($filterRegions[0])) {
            $searchFilter[] = ['regions.id', $filterRegions[0]->id];
            $regionID = $filterRegions[0]->id;
        }

        $queries = DB::table('queries')
            ->select(DB::raw("queries.name as query_name, queries.id as query_id, queries.query_group_id as query_group_id"))
            ->whereIn('queries.query_group_id', $groupQueryIDs)
            ->where(function ($q) use ($regionID) {
                $q->where('region_id', $regionID)
                    ->orWhere('region_id', '0')
                    ->orWhere('region_id', NULL);
            })

            ->where('is_active', 1)
            ->orderBy('queries.name', 'asc')
            ->get();

        //dd($queries);
        // $queryIDs = Query::
        // whereIn('query_group_id', $queryGroupIDs)
        // ->pluck('id')->toArray();
        $query_group_ids = $queries->pluck('query_group_id')->toArray();
        $query_ids = $queries->pluck('query_id')->toArray();


        $query_group_ids = QueryGroup::whereIn('id', $query_group_ids)->get()->toArray();


        $sqlQuery = DB::table('positions')
            ->select(DB::raw("IF(yandex_date IS NOT NULL, yandex_date, google_date) as action_date"))
            ->join('queries', 'queries.id', '=', 'positions.query_id')
            ->join('regions', 'regions.id', '=', 'positions.region_id')
            ->where($searchFilter)
            ->where(function ($q) use ($startTime) {
                $q->where('yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('google_date', '>=', $startTime->format('Y-m-d'));
            })
            ->where(function ($q) use ($endTime) {
                $q->where('yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('google_date', '<=', $endTime->format('Y-m-d'));
            });
        if ($filterQueryGroupID > 0) {
            $sqlQuery->whereIn('queries.query_group_id', $groupQueryIDs);
        }
        $dates = $sqlQuery->groupBy('action_date')
            ->orderBy('action_date', 'desc')
            ->get();

        //DB::enableQueryLog();
        $sqlQuery = DB::table('positions')
            ->select(DB::raw("IF(yandex_date IS NOT NULL, yandex_date, google_date) as action_date, regions.name as region_name, positions.* "))
            ->join('queries', 'queries.id', '=', 'positions.query_id')
            ->join('regions', 'regions.id', '=', 'positions.region_id')
            ->where($searchFilter)
            ->where(function ($q) use ($startTime) {
                $q->where('yandex_date', '>=', $startTime->format('Y-m-d'))
                    ->orWhere('google_date', '>=', $startTime->format('Y-m-d'));
            })
            ->where(function ($q) use ($endTime) {
                $q->where('yandex_date', '<=', $endTime->format('Y-m-d'))
                    ->orWhere('google_date', '<=', $endTime->format('Y-m-d'));
            });

        if ($filterQueryGroupID > 0) {
            $sqlQuery->whereIn('queries.query_group_id', $groupQueryIDs);
        }
        $positions = $sqlQuery->orderBy('action_date', 'desc')
            ->get();

        $regions = ProjectRegion::with('region:id,name')
            ->where('project_id', session('selected_project_id'))
            ->where('region_id', $regionID)
            ->get();

        $groupedPositions = [];
        foreach ($positions as $key => $position) {
            $seKey = 'yandex';
            $data['region'] = $position->region_name;
            $data['full_url'] = $position->full_url;


            if (is_null($position->yandex_date)) {
                $seKey = 'google';
                $data['position'] = $position->google_position;
            } else {
                $data['position'] = $position->yandex_position;
            }



            $data['position_m'] = Position::find($position->id);
            $groupedPositions[$position->query_id . '--' . $position->action_date][$seKey][] = $data;

            //$groupedPositions[$position->query_id] = $position->full_url;
        }



        // DB::enableQueryLog();
        // dd(DB::getQueryLog());
        $region_idz = ProjectRegion::where('project_id', session('selected_project_id'))->pluck('region_id');
        $region_to_filter = Region::whereIn('id', $region_idz)->get();
        //dd($queries);
        $projectCompetitors = Competitor::where('project_id', session('selected_project_id'))->get();
        //dd($dates);
        $alldata =  [

            'regions'           => $regions,
            'project'           => $project,
            'queries'           => $queries,
            'group_regions'     => $groupRegions,
            'dates'             => $dates,
            'qgis'              => $query_group_ids,
            'qis'               => $query_ids,
            'positions'         => $groupedPositions,
            'region_to_filter'  => $region_to_filter,
            'competitors'       => $projectCompetitors,
            'region_id'         => $regionID,
            'filters'           => [
                'query_groups'      => $groupQueries,
                'regions'           => $filterRegions,
                'values'            => [
                    'start_date'    => $startTime->format('d.m.Y'),
                    'end_date'      => $endTime->format('d.m.Y'),
                    'query_group_id' => null,
                    'search_engine' => null,
                    'region_id'     => null,
                ]
            ]
        ];
        $c = $alldata;
        $alldata['c'] = $c;
        //dd($regions->region_id);
        // dd($alldata);
        // return view('project.position',$alldata);
        return $alldata;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function schedule(Request $request)
    {
        $this->authorize('schedule', Schedule::class);




        if (!isset($request->schedule)) {
            $request->merge([
                'schedule' => [
                    "type" => "fixed_date",
                    "dates" => Carbon::now()->format('Y-m-d'),
                    "time" => Carbon::now()->addMinutes(2)->format('H:i'),
                    "project_id" => $request->project_id
                ],
                "name" => Carbon::now() . " p_id: " . $request->project_id

            ]);
        }




        if ($request) {

            $time =  $request->schedule['time'];
            if ($request->schedule['time'] == null || $request->schedule['time'] == "") {
                $time = "00:00";
            }

            if (isset($request->schedule['task_id'])) {
                //return "updating..";
                $task = Task::where('id', $request->schedule['task_id'])
                    ->update([
                        'project_id' => $request->schedule['project_id'],
                        'name' => $request->name
                    ]);
                $task = Task::where('id', $request->schedule['task_id'])->first();
                Schedule::where('id', $request->schedule['task_id'])
                    ->update([
                        'type' => $request->schedule['type'],
                        'date' => $request->schedule['dates'],
                        'time' => $time,
                        'name' => $request->name,
                        'status' => false,
                        'task_id' => $request->schedule['task_id']
                    ]);
                ReadyScheduledPosition::where('task_id', $request->schedule['task_id'])
                    ->update([
                        'action' => $request->data['action'],
                        'filter' => $request->data['filter'],
                        'google' => isset($request->data['google']) ? implode(",", $request->data['google']) : '',
                        'query_group' => isset($request->data['query_group']) ? implode(",", $request->data['query_group']) : '',
                        'yandex' => isset($request->data['yandex']) ? implode(",", $request->data['yandex']) : '',
                        'project_id' => $request->project_id,
                        'task_id' => $request->schedule['task_id']
                    ]);
            } else {

                $task = new Task();
                $task->project_id = $request->schedule['project_id'];
                $task->name = $request->name;
                $task->save();

                $result = Schedule::create([
                    'type' => $request->schedule['type'],
                    'date' => $request->schedule['dates'],
                    'time' => $time,
                    'name' => $request->name,
                    'status' => false,
                    'task_id' => $task->id,
                    'project_id' => $request->schedule['project_id']
                ]);

                ReadyScheduledPosition::create([
                    'action' => $request->data['action'],
                    'filter' => $request->data['filter'],
                    'google' => isset($request->data['google']) ? implode(",", $request->data['google']) : '',
                    'query_group' => isset($request->data['query_group']) ? implode(",", $request->data['query_group']) : '',
                    'yandex' => isset($request->data['yandex']) ? implode(",", $request->data['yandex']) : '',
                    'project_id' => $request->project_id,
                    'task_id' => $task->id
                ]);
            }
        }
        return true;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Task $task
     * @return void
     */
    public function edit(Task $task)
    {
        return view('project.schedule.edit', ['task' => $task, 'f' => $this->filters()]);
        //dump($task->schedeuled_positions);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $task = Task::find($id);
        //dd($task);
        $task->schedules->first()->delete();
        $task->ready_scheduled_position->delete();


        $task->delete();
        return  redirect()->back()->with('success_message', [__('Schedule is deleted')]);
    }

    public function testing()
    {
        $t = Task::find(2);
        $sps = $t->scheduled_positions();
        dump($sps);
        //dump($sps->toArray());
        $data = array();
        foreach ($t->scheduled_positions as $k) {

            $data[] = [
                'method' => $k->method,
                'word' => $k->word,
                'region' => $k->region,
                'query_id' => $k->query_id,
                'region_id' => $k->region_id,
            ];
        }

        dump($data);
    }
    public function send_to_tg_bot($message, $from)
    {

        $message1 = 'Снятие  <strong>"' . $from . '"</strong>:' . PHP_EOL;


        $message1 .= $message;



        //companycode - Индивидуальный код организации (получить у администратора)
        $data = ["companycode" => "co19a1ddfa37041", "data" => [["message" => $message1]]];
        $url = 'https://t.kuleshov.studio/api/getmessages';
        $data_string = json_encode($data);

        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return true;
    }
    public function make_ready($ready)
    {
        //$ready =  ReadyScheduledPosition::where('task_id',8)->first();
        // dd($ready);
        if ($ready === null) {
            //dd(1);
            return false;
        }


        $b = [
            'project_id' => $ready->project_id,
            "data" =>
            [
                'action' => $ready->action,
                "filter" => $ready->filter,
                "query_group" => explode(',', $ready->query_group),
                "yandex" => explode(',', $ready->yandex),
                "google" => explode(',', $ready->google),
            ]
        ];

        $request = new Request($b);
        $datas =  $this->getAllPositions($request, $ready->task->id);
        //dd($datas);
        foreach ($datas as $data) {
            $data['task_table_id'] = $ready->task->id;
            //dump($data);
            PendingQuery::create($data);
            //GetTaskids::dispatch($data,$ready_pos);
        }


        return true;
    }
    public function getAllPositions(Request $request, $task_id)
    {

        $ApiInterface = resolve(ApiInterface::class);
        $queriesQuery = DB::table('queries')
            ->select(DB::raw("queries.name as query_name, queries.id as query_id, regions.name as region_name, regions.id as region_id, regions.yandex_index, regions.google_index"))
            ->join('query_groups', 'query_groups.id', '=', 'queries.query_group_id')
            ->leftJoin('regions', 'regions.id', '=', 'queries.region_id');

        $queriesQuery
            ->whereIn('query_groups.id', $request['data']['query_group'])
            ->where([
                ['query_groups.project_id', $request->project_id],
                ['query_groups.is_active', 1],
                ['queries.is_active', 1]
            ]);

        $queries = $queriesQuery
            ->orderBy('queries.name', 'asc')
            ->get();
        //return $queries;

        //dd($queries);
        $regionIds = isset($request->data['yandex']) ? $request->data['yandex'] : [];
        if (isset($request->data['google'])) {
            $regionIds = array_unique(array_merge($regionIds, $request->data['google']));
        }
        $project_regions = ProjectRegion::where('project_id', $request->project_id)->get();
        //dd($project_regions);
        $regions = Region::whereIn('id', $regionIds)->get();

        $data = [];
        $newq = [];
        //Adding to  unassigned queries all regions

        $yandex =   isset($request->data['yandex']);
        $google =   isset($request->data['google']);
        if ($request->data['filter'] == "byall") {
            foreach ($queries as $key => $query) {
                if (!$query->region_id) {
                    foreach ($project_regions as $project_region) {
                        $r =  Region::where('id', $project_region->region_id)->get();
                        $newq[] = (object) array(
                            'google_index' => $r[0]->google_index,
                            'query_id' => $query->query_id,
                            'query_name' => $query->query_name,
                            'region_id' => $project_region->region_id,
                            'region_name' => $r[0]->name,
                            'yandex_index' => $r[0]->yandex_index,

                        );
                        $yandex = $r[0]->yandex_index;
                        $google = $r[0]->google_index;
                    }
                } else {
                    $newq[] = $query;
                }
            }
            $queries = $newq;
        }


        foreach ($queries as $key => $query) {

            if ($yandex) {
                if ($query->region_id !== null) {
                    $data[] = [
                        'method' => 'yandex',
                        'word' => $query->query_name,
                        'region' => $query->yandex_index,
                        'query_id' => $query->query_id,
                        'region_id' => $query->region_id,
                        'project_id' => $request->project_id,
                    ];
                } else if ($query->region_id == null) {
                    foreach ($regions as $key => $region) {
                        if (in_array($region->id, $request->data['yandex']) === true) {
                            $data[] = [
                                'method' => 'yandex',
                                'word' => $query->query_name,
                                'region' => $region->yandex_index,
                                'query_id' => $query->query_id,
                                'region_id' => $region->id,
                                'project_id' => $request->project_id,
                            ];
                        }
                    }
                }
            }
            if ($google) {
                if ($query->region_id !== null) {
                    $data[] = [
                        'method' => 'google',
                        'word' => $query->query_name,
                        'region' => $query->google_index,
                        'query_id' => $query->query_id,
                        'region_id' => $query->region_id,
                        'project_id' => $request->project_id,
                    ];
                } else if ($query->region_id == null) {
                    foreach ($regions as $key => $region) {
                        if (in_array($region->id, $request->data['google']) === true) {
                            $data[] = [
                                'method' => 'google',
                                'word' => $query->query_name,
                                'region' => $region->google_index,
                                'query_id' => $query->query_id,
                                'region_id' => $region->id,
                                'project_id' => $request->project_id,
                            ];
                        }
                    }
                }
            }
        }
        //dump(($request->all()));
        //dump($data);

        foreach ($data as $keys => $d) {
            if (isset($request->data[$d['method']])) {
                if (!in_array($d['region_id'], $request->data[$d['method']])) {
                    unset($data[$keys]);
                }
            }
        }

        $existingPositions = [];
        $searchFilters = [];
        //deleting todays positions
        foreach ($data as $key => $singleData) {
            $searchFilter = [
                ['query_id', $singleData['query_id']],
                ['region_id', $singleData['region_id']],
                ['project_id', $singleData['project_id']],
                ['updated_at', '>=', Carbon::yesterday()->startOfDay()]
            ];
            if ($singleData['method'] == 'yandex') {
                $searchFilter[] = ['method', 'yandex'];
            }
            if ($singleData['method'] == 'google') {
                $searchFilter[] = ['method', 'google'];
            }
            $searchFilters[] = $searchFilter;
        }
        if (count($searchFilters) > 0) {
            $existingPositionQuery = DB::table('positions')->select('id');
            foreach ($searchFilters as $singleSearchFilter) {
                $existingPositionQuery->orWhere(function ($query) use ($singleSearchFilter) {
                    $query->where($singleSearchFilter);
                });
            }
            $ids = $existingPositionQuery->pluck('id')->toArray();

            if (count($ids) > 0) {
                // Position::destroy($ids);
            }
        }
        // 0554770123 Sereja
        if (count($data) < 1) {
            session()->flash('warning_message', [__('All Position were updated for today')]);
            return 0;
        }
        array_splice($data, 0, 0);


        //dd($data);
        return $data;
    }
}
