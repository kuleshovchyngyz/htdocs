<?php

namespace App\Http\Controllers;

use App\Competitor;
use App\CompetitorRegion;
use App\Project;
use App\ProjectRegion;
use App\QueryGroup;
use App\Region;
use Illuminate\Http\Request;

class CompetitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        session(['selected_project_id' => $id]);
        $projectCompetitors = Competitor::where('project_id', session('selected_project_id'))->get();
        return view('project.competitor.index', [
            'items' => $projectCompetitors
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $projectRegions = ProjectRegion::with('region')
            ->where('project_id', session('selected_project_id'))
            ->orderBy('url', 'asc')
            ->paginate(config('app.paginate_by', '25'))->onEachSide(2);



        //dd($projectCompetitors->competitorregions->toArray());

        return view('project.competitor.create', [
            'items' => $projectRegions

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $request->validate([
            'name' => 'required',
            'url' => 'required',

        ]);
        //dd($request->all());
        $project = Competitor::create(
            [
                "name" => $request['name'],
                "is_active" => $request['is_active'],
                "url" => $request['url'],
                "project_id" => session('selected_project_id'),
            ]
        );

        if ($project) {
            $i = 0;
            while ($i < $request["numberofregions"]) {
                if (isset($request['region' . $i])) {
                    CompetitorRegion::create([
                        "region_id" => $request['region' . $i],
                        "competitor_id" => $project->id
                    ]);
                }
                $i++;
            }
        }


        if (!$project) {
            return redirect()->route('project.competitor.create')->with('error_message', [__('Competitor was not created')]);
        }

        return redirect()->route('project.competitor.create')->with('success_message', [__('Competitor was created')]);
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
     * @param Competitor $competitor
     * @return void
     */
    public function edit(Competitor $competitor)
    {
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $projectRegions = ProjectRegion::with('region')
            ->where('project_id', session('selected_project_id'))
            ->orderBy('region_id', 'asc')
            ->paginate(config('app.paginate_by', '25'))->onEachSide(2);


        $diff = array();
        foreach ($projectRegions as $key => $region) {
            $check = "";
            foreach ($competitor->competitorregions as $item) {
                if ($item->region_id == $region->region_id) {
                    $check = "checked";
                }
            }
            $diff[$region->region_id] = $check;
        }


        return view('project.competitor.edit', [
            'competitor' => $competitor,
            'projectregions' => $projectRegions,
            'diff' => $diff
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Competitor $competitor)
    {
        //dd($request->all());
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $projectRegions = ProjectRegion::with('region')
            ->where('project_id', session('selected_project_id'))
            ->orderBy('region_id', 'asc')
            ->paginate(config('app.paginate_by', '25'))->onEachSide(2);
        $request->validate([
            'name' => 'required',
            'url' => 'required',

        ]);
        $result = $competitor->update([
            "name" => $request["name"],
            "url" => $request["url"],
            "is_active" => $request["is_active"]
        ]);

        $i = 0;
        $arr = array();
        while ($i < $projectRegions->count()) {
            if (isset($request['region' . $i])) {
                $arr[] = $request['region' . $i];
            }
            $i++;
        }


        $diff = array();

        foreach ($projectRegions as $item) {
            $check = false;
            foreach ($arr as $region) {
                if ($item->region_id == $region) {
                    $check = true;
                }
            }
            $diff[$item->region_id] = $check;
        }
        //dd($competitor->id);
        foreach ($competitor->competitorregions as $region) {
            $region->delete();
        }

        foreach ($diff as $key => $r) {
            if ($r) {
                CompetitorRegion::create([
                    'region_id' => $key,
                    'competitor_id' => $competitor->id
                ]);
            }
        }





        //  die;


        return ($result)
            ? redirect()->route('project.competitor.index')->with('success_message', [__('Query is updated')])
            : redirect()->route('project.competitor.index')->with('error_message', [__('Query is not updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Competitor $competitor)
    {
        if ($competitor) {
            foreach ($competitor->competitorregions as $c) {
                $c->delete();
            }
            $result = $competitor->delete();

            return ($result)
                ? redirect()->route('project.competitor.index')->with('success_message', [__('Competitor is deleted')])
                : redirect()->route('project.competitor.index')->with('error_message', [__('Competitor is not deleted')]);
        }
    }

    public function archive(Competitor $competitor)
    {
        if (isset($competitor)) {
            $result = $competitor->update([
                'is_active' => !$competitor->is_active
            ]);
            return ($result)
                ? redirect()->route('project.competitor.index')->with('success_message', [__('Competitor is archived')])
                : redirect()->route('project.competitor.index')->with('error_message', [__('Competitor is not archived')]);
        }
        return redirect()->route('project.competitor.index')->with('error_message', [__('Wrong ID is provided')]);
    }
}
