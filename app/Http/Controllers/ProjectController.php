<?php

namespace App\Http\Controllers;

use App\Region;
use App\QueryGroup;
use App\Query;
use App\Project;
use App\support\Filter\Filter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */

    public function index()
    {

        $projects = DB::table('projects')->select(DB::raw("ROUND(AVG(positions.yandex_position)) as average_position, max(positions.created_at) as latest_date, projects.* "))
            ->leftJoin('positions', 'projects.id', '=', 'positions.project_id')
            ->groupBy('projects.id')
            ->orderBy('latest_date', 'desc')
            ->paginate(config('app.paginate_by', '25'))
            ->onEachSide(2);

        return view('project.index', ['projects' => $projects,]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Project $project
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function position(Project $project, Request $request)
    {

        session(['selected_project_id' => $project->id]);
        $this->authorize('position', $project, Project::class);
        $filter = new Filter($project, $request);
        return view('project.position', $filter->filter());
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $regions = Region::all();
        $queryGroups = QueryGroup::all();
        if (count($queryGroups) == 0) {
            return redirect()->route('query-group.create')
                ->with('error_message', [__('Please create and assign query group to project')]);
        }
        return view('home', ['regions' => $regions, 'query_groups' => $queryGroups, 'manager_id' => \Auth::id(),]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $is_active = 1)
    {

        $request->validate([
            'name' => 'required',
            'url' => 'required',
        ]);

        $project = Project::create($request->all());
        if (!$project) {
            return redirect()->route('home')
                ->with('error_message', [__('Project was not created')]);
        }

        return redirect()
            ->route('home')
            ->with('success_message', [__('Project was created')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function select(Project $project)
    {
        if (!$project) {
            return redirect()->route('project.index')
                ->with('error_message', [__('Something went wrong')]);
        }
        session(['selected_project_id' => $project->id]);
        return redirect()
            ->route('project.position', $project->id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function brief(Project $project)
    // {
    //     if (!$project) {
    //         return redirect()->route('home')
    //             ->with('error_message', [__('Something went wrong')]);
    //     }
    //     session(['selected_project_id' => $project->id]);
    //     return redirect()
    //         ->route('project.brief', $project->id);
    // }


    /**
     * Display the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $regions = Region::all();
        $queryGroups = QueryGroup::all();
        return view('project.edit', ['item' => $project, 'regions' => $regions, 'query_groups' => $queryGroups,]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {

        //dd($project->toArray());
        $result = $project->update($request->all());

        return ($result) ? redirect()->route('home', [$project])->with('success_message', [__('Project is updated')]) : redirect()
            ->route('home', [$project])->with('error_message', [__('Project is not updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function archive(Project $project)
    {

        $project->update(['is_active' => !$project->is_active]);
        $result = $project->is_active;
        return ($result)
            ?
            redirect()->route('home')->with('success_message', [__('Проект разархивирован')])
            :
            redirect()->route('home')->with('success_message', [__('Проект архивирован')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {

        if (isset($project)) {
            $queryGroupIDs = QueryGroup::where('project_id', $project->id)
                ->orderBy('parent_group_id', 'asc')
                ->pluck('id')
                ->toArray();

            $queryIDs = Query::whereIn('query_group_id', $queryGroupIDs)->pluck('id')
                ->toArray();

            QueryGroup::destroy($queryGroupIDs);

            Query::destroy($queryIDs);

            $result = Project::destroy($project->id);
            return ($result) ? redirect()->route('home')
                ->with('success_message', [__('Project is deleted')]) : redirect()
                ->route('home')
                ->with('error_message', [__('Project is not deleted')]);
        }
        return redirect()
            ->route('home')
            ->with('error_message', [__('Wrong ID is provided')]);
    }

    public function multyplydestroy(Request $request)
    {
        $result = Project::destroy($request->ids);
        return ($result) ? redirect()->route('home')
            ->with('success_message', [__('Project is deleted')]) : redirect()
            ->route('home')
            ->with('error_message', [__('Project is not deleted')]);
    }

    public function multyplyarchive(Request $request, Project $project)
    {
        $projectChecked = Project::whereIn('id', $request->ids)->get();
        $statusProject = $projectChecked[0]->is_active;

        Project::whereIn('id', $request->ids)->update(['is_active' => !$statusProject]);

        return ($statusProject) ? redirect()->route('home')
            ->with('warning_message', [__('Проекты в архиве')]) : redirect()
            ->route('home')
            ->with('success_message', [__('Проекты восстановлены')]);
    }
}
