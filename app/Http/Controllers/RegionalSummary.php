<?php

namespace App\Http\Controllers;

use App\Project;
use App\support\Filter\Filter;
use App\support\Statistics\StatisticService;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegionalSummary extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Project $project)
    {
        session(['selected_project_id' => $project->id]);
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $p = Project::find(session('selected_project_id'));
        // $request = app(Request::class);
        //dd($p);

        $filter = new Filter($p, $request);
        $filter->set_dates();
        $Statistics = [];
        $method = 'yandex';
        if (isset($request->filter_search_engine) && $request->filter_search_engine != 'all') {
            $method = $request->filter_search_engine;
        }
        foreach ($filter->regions as $region) {
            $Statistics[] = new StatisticService($filter->startTime, $filter->endTime, $method, $region->id, $filter->get_query_ids($region->id));
        }

        $arr = [];
        foreach ($filter->regions as $region) {
            $arr[$region->id] = $region->name;
        }




        return view('project.regionalsummary.index', [
            'regions' => $arr,
            'dates' => $filter->dates,
            'filter_search_engine' => $method,
            'startTime' => $filter->startTime,
            'endTime' => $filter->endTime,
            'region_datas' => $Statistics
        ]);
    }
    public function summary(Request $request)
    {
        dd($request->all());
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }
}
