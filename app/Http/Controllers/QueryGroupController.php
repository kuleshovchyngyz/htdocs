<?php

namespace App\Http\Controllers;

use App\Project;
use App\ProjectRegion;
use App\QueryGroup;
use App\Query;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class QueryGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Project $project)
    {
        session(['selected_project_id' => $project->id]);

        $this->authorize('index', QueryGroup::class);
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')
                ->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $result = QueryGroup::with('region')->where('project_id', session('selected_project_id'))
            ->orderBy('parent_group_id', 'asc')
            ->get();

        $groupQueries = QueryGroup::getAllChildren($result, 0);
        $tree = QueryGroup::toTree($result);

        $duplicateQueries = DB::table('queries')->select(DB::raw('count(queries.name) as qnt, queries.name'))
            ->join('query_groups', 'query_groups.id', 'queries.query_group_id')
            ->where([['query_groups.project_id', session('selected_project_id')], ['queries.is_active', true]])
            ->groupBy('queries.name')
            ->having(DB::raw('count(queries.name)'), '>', 1)
            ->get()
            ->toArray();

        if (count($duplicateQueries) > 0) {
            session()->flash('error_message', [__('Duplicate queries found: ') . implode(', ', array_column($duplicateQueries, 'name'))]);
        }
        $regions = ProjectRegion::with('region:id,name')->where('project_id', session('selected_project_id'))
            ->get()
            ->sortBy('name');
        return view('query-group.index', ['items' => $tree, 'group_queries' => $groupQueries, 'regions' => $regions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($parent_group_id = 0)
    {
        $this->authorize('create', QueryGroup::class);
        $queryGroups = QueryGroup::orderBy('parent_group_id', 'asc')->get();
        return view('query-group.create', ['parent_group_id' => $parent_group_id, 'query_groups' => $queryGroups, 'regions' => Region::all()->sortBy('name')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('store', QueryGroup::class);
        $request->validate(['name' => 'required', 'project_id' => 'required|integer|gt:0', 'region_id' => 'nullable|integer', 'parent_group_id' => 'present|nullable|integer',]);

        $project = QueryGroup::create($request->all());

        if (!$project) {
            session()->flash('error_message', [__('Query Group was not created')]);
            return 0;
        }
        session()
            ->flash('success_message', [__('Query Group was created')]);
        return 1;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QueryGroup  $queryGroup
     * @return \Illuminate\Http\Response
     */
    public function show(QueryGroup $queryGroup)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QueryGroup  $queryGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(QueryGroup $queryGroup)
    {
        $this->authorize('edit', QueryGroup::class);
        $result = QueryGroup::where('project_id', session('selected_project_id'))->orderBy('parent_group_id', 'asc')
            ->get();
        $queryGroups = QueryGroup::getAllChildren($result, 0);

        return view('query-group.edit', ['item' => $queryGroup, 'query_groups' => $queryGroups, 'regions' => Region::all()->sortBy('name')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QueryGroup  $queryGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QueryGroup $queryGroup)
    {
        $this->authorize('update', QueryGroup::class);
        $request->validate(['name' => 'required', 'project_id' => 'required|integer|gt:0', 'region_id' => 'nullable|integer', 'parent_group_id' => 'present|nullable|integer',]);

        $result = $queryGroup->update($request->all());
    }
    public function addtarget(Request $request, QueryGroup $queryGroup)
    {
        // return $request->all();
        $this->authorize('addtarget', QueryGroup::class);
        $target = $request["target_path"];
        $pattern_start = "/^\/+/";
        $pattern_end = "/\/+$/";
        if (preg_match($pattern_start, $target)) {
            $target = preg_split($pattern_start, $target);
            $target = $target[1];
        }
        if (preg_match($pattern_end, $target)) {
            $target = preg_split($pattern_end, $target);
            $target = $target[0];
        }

        $s = QueryGroup::whereRaw('id=' . $queryGroup["id"])->update(['target_path' => $target]);

        $children = QueryGroup::select('id')->whereRaw('id=' . $queryGroup["id"])->get();
        $l = array(
            'id' => $queryGroup["id"]
        );
        $children[count($children)] = $l;

        $result = $queryGroup->update(['target_path' => $target]);

        //        array_push($stack,$s,$request->all(),$request);
        if ($s > 0 && $request["target_path"] != "") {
            $children[count($children)] = [__('Target page is added to Query Group')];
        } else if ($s > 0 && $request["target_path"] == "") {
            $children[count($children)] = [__('Target page is removed')];
        } else {
            $children[count($children)] = [__('Query Group is not updated')];
        }
        return $children;
    }
    /**
     * Rename the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QueryGroup  $queryGroup
     * @return \Illuminate\Http\Response
     */
    public function ajaxRename(Request $request, QueryGroup $queryGroup)
    {
        $result = $queryGroup->update($request->all());
        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function archive(QueryGroup $queryGroup)
    {
        $this->authorize('archive', QueryGroup::class);
        if (isset($queryGroup)) {
            $result = $queryGroup->update(['is_active' => !$queryGroup->is_active]);
            return ($result) ? redirect()->route('query-group.index')
                ->with('success_message', [__('Query Group is archived')]) : redirect()
                ->route('query-group.index')
                ->with('error_message', [__('Query Group is not archived')]);
        }
        return redirect()
            ->route('query-group.index')
            ->with('error_message', [__('Wrong ID is provided')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(QueryGroup $queryGroup)
    {
        $this->authorize('destroy', QueryGroup::class);
        if (isset($queryGroup)) {
            $result = QueryGroup::where(function ($q) use ($queryGroup) {
                $q->where('parent_group_id', '>=', $queryGroup->id)
                    ->orWhere('id', $queryGroup->id);
            })
                ->orderBy('parent_group_id', 'asc')
                ->get();
            $groupQueries = QueryGroup::getAllChildren($result, $queryGroup->id);
            $groupQueryIDs = array_column($groupQueries, 'id');

            $result = Query::whereIn('query_group_id', $groupQueryIDs)->delete();

            $result = QueryGroup::destroy($groupQueryIDs);
            return 1;
        }
        return 0;
    }
}
