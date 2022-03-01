<?php

namespace App\Http\Controllers;

use App\Jobs\GetPositions;
use App\Jobs\GetTaskids;
use App\Jobs\SendTelegramJob;
use App\Position;
use App\Project;
use App\ReadyScheduledPosition;
use App\Region;
use App\ProjectRegion;
use App\Query;
use App\Schedule;
use App\Test;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Interfaces\ApiInterface;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, ApiInterface $ApiInterface)
    {
        // $project = Project::find(5);
        // $region = Region::find(164);
        // $query = Query::find(4);
        // $ddd = [
        //     'method' => 'yandex',
        //     'word' => $query->name,
        //     'region' => $region->getAttributes(),
        //     'project_url' => $project->url,
        // ];
        // $position = $ApiInterface->getPosition( $ddd );

        // $project = Position::create([
        //     $ddd['method'] . '_position' => $position,
        //     $ddd['method'] . '_date' => Carbon::now(),
        //     'project_id' => $project->id,
        //     'region_id' => $region->id,
        //     'query_id' => $query->id,
        // ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPosition(Request $request, ApiInterface $ApiInterface)
    {
        $request->validate([
            'method'=>'required|in:yandex,google',
            'project_id'=>'required|integer',
            'region_id'=>'required|integer',
            'query_id'=>'required|integer',
        ]);
        $project = Project::find($request->project_id);
        $region = Region::find($request->region_id);
        $query = Query::find($request->query_id);
        $parameters = [
            'method' => $request['data']['method'],
            'word' => $query->name,
            'region' => $region->getAttributes(),
            'project_url' => $project->url,
        ];
        try {
            $position = $ApiInterface->getPosition( $parameters );

            if (is_integer($position) && $position >= 0) {
                $position = Position::create([
                    $parameters['method'] . '_position' => $position,
                    $parameters['method'] . '_date' => Carbon::now(),
                    'project_id' => $project->id,
                    'region_id' => $region->id,
                    'query_id' => $query->id,
                ]);
                session()->flash('success_message', [__('Position Updated')]);

            }
            else if($position >= '-2') {
                session()->flash('error_message', [__('Region doesn\'t have search engine key')]);
            }
            else if($position >= '-3') {
                session()->flash('error_message', [__('This site wasn\'t found on search result list by this query')]);
            }
            else if($position >= '-4') {
                session()->flash('error_message', [__('MegaIndex server is not responding. Please try later')]);
            }
            else {
                session()->flash('error_message', [__('Something went wrong')]);
            }
        }
        catch (Exception $e) {
            session()->flash('error_message', [__('Something went wrong')]);
        }

    }

    public function make_ready(){
        //dd(444);
        GetPositions::dispatch('23')->delay(now()->addSeconds(1));

    }
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @param ApiInterface $ApiInterface
     * @return int
     */

    public function getAllPositions(Request $request, ApiInterface $ApiInterface)
    {
       // $ApiInterface = ApiInterface::class;
        //return 888888888;
        //return ( $request->all());
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
        $regionIds = isset($request->data['yandex']) ? $request->data['yandex'] : [];
        if (isset($request->data['google'])) {
            $regionIds = array_unique(array_merge($regionIds, $request->data['google']));
        }
        $project_regions = ProjectRegion::where('project_id',$request->project_id)->get();

        $regions = Region::whereIn('id', $regionIds)->get();

        $data = [];
        $newq = [];
        //Adding to  unassigned queries all regions

        $yandex =   isset($request->data['yandex']);
        $google =   isset($request->data['google']);
        if($request->data['filter']=="byall"){
            foreach ($queries as $key => $query) {
                if(!$query->region_id){
                    foreach ($project_regions as $project_region) {
                        $r =  Region::where('id', $project_region->region_id)->get();
                        $newq[]= (object) array(
                            'google_index' => $r[0]->google_index,
                        'query_id' => $query->query_id,
                        'query_name' => $query->query_name,
                        'region_id' => $project_region->region_id,
                        'region_name' => $r[0]->name,
                        'yandex_index' => $r[0]->yandex_index,

                        );
                        $yandex= $r[0]->yandex_index;
                        $google = $r[0]->google_index;

                    }
                }else{
                    $newq[] = $query;
                }
            }
            $queries = $newq;
        }


        foreach ($queries as $key => $query) {

            if ($yandex) {
                if ($query->region_id !== null ) {
                    $data[] = [
                        'method' =>'yandex',
                        'word' => $query->query_name,
                        'region' => $query->yandex_index,
                        'query_id' => $query->query_id,
                        'region_id' => $query->region_id,
                        'project_id' => $request->project_id,
                    ];
                }
                else if ($query->region_id == null) {
                    foreach ($regions as $key => $region) {
                        if (in_array($region->id, $request->data['yandex']) === true ) {
                            $data[] = [
                                'method' =>'yandex',
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
                if ($query->region_id !== null ) {
                    $data[] = [
                        'method' =>'google',
                        'word' => $query->query_name,
                        'region' => $query->google_index,
                        'query_id' => $query->query_id,
                        'region_id' => $query->region_id,
                        'project_id' => $request->project_id,
                    ];
                }
                else if ($query->region_id == null) {
                    foreach ($regions as $key => $region) {
                        if (in_array($region->id, $request->data['google']) === true) {
                            $data[] = [
                                'method' =>'google',
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
                $existingPositionQuery->orWhere(function($query) use ($singleSearchFilter) {
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



        //return  $this->where($queries,54,'google_index');
        return $data;
       // return $queries;


        //return $taskIDs;
        session()->flash('success_message', [__('Positions were updated. Results will be available after few minutes')]);
        return 1;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $array
     * @param $var
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function where($array,$var,$type)
    {
        if($type=="query_name"){
            foreach ( $array as $item) {
                if($item->query_name==$var){
                    return $item->query_id;
                }
            }
        }else if(substr($type,-5)=='index'){
            foreach ($array as $item) {
               if($item->$type==$var){
                   return $item->region_id;
               }
            }
        }

    }
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Position $position)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        //
    }
}
