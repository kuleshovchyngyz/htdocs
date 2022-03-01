<?php

namespace App\Http\Controllers;

use App\ProjectRegion;
use App\Region;
use App\Position;
use App\Project;
use App\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\support\QueryPosition;

class ProjectRegionController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index($id)
    {

        session(['selected_project_id' => $id]);
        if (null === session('selected_project_id')) {
            return redirect()->route('project.index')->with('error_message', [__('Please select a Project from sidebar')]);
        }
        $projectRegions = ProjectRegion::with('region')
            ->where('project_id', session('selected_project_id'))
            ->orderBy('url', 'asc')
            ->paginate(config('app.paginate_by', '25'))->onEachSide(2);

        return view('project.region.index', [
            'items' => $projectRegions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', ProjectRegion::class);
        $regions = Region
            ::select(['name', 'id'])
            ->get()
            ->sortBy('name');
        return view('project.region.create', [
            'regions' => $regions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required',
            'region_id' => 'required|integer',
        ]);
        $requests = $request->all();
        $requests['project_id'] = session('selected_project_id');
        $result = ProjectRegion::create(
            $requests
        );

        if (!$result) {
            return redirect()->route('project.region.index')->with('error_message', [__('Project region was not created')]);
        }
        return redirect()->route('project.region.index')->with('success_message', [__('Project region was created')]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectRegion $projectRegion
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(ProjectRegion $projectRegion)
    {
        $regions = Region
            ::select(['name', 'id'])
            ->get()
            ->sortBy('name');
        return view('project.region.edit', [
            'item' => $projectRegion,
            'regions' => $regions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProjectRegion $projectRegion
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(Request $request, ProjectRegion $projectRegion)
    {
        $request->validate([
            'url' => 'required',
            'region_id' => 'required|integer',
        ]);
        $requests = $request->all();
        $requests['project_id'] = session('selected_project_id');
        $result = $projectRegion->update($requests);

        return ($result)
            ? redirect()->route('project.region.edit', [$projectRegion])->with('success_message', [__('Project region is updated')])
            : redirect()->route('project.region.edit', [$projectRegion])->with('error_message', [__('Project is region not updated')]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectRegion  $projectRegion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive(ProjectRegion $projectRegion)
    {
        if (isset($projectRegion)) {
            $result = $projectRegion->update([
                'is_active' => !$projectRegion->is_active
            ]);
            return ($result)
                ? redirect()->route('project.region.index')->with('success_message', [__('Project region is archived')])
                : redirect()->route('project.region.index')->with('error_message', [__('Project region is not archived')]);
        }
        return redirect()->route('project.region.index')->with('error_message', [__('Wrong ID is provided')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectRegion  $projectRegion
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ProjectRegion $projectRegion)
    {
        if (isset($projectRegion)) {

            $result = $projectRegion->delete();


            Position::where('region_id', $projectRegion->region_id)
                ->delete();

            Query::where('region_id', $projectRegion->region_id)
                ->update(['region_id' => -1]);


            return ($result)
                ? redirect()->route('project.region.index')->with('success_message', [__('Project region is deleted')])
                : redirect()->route('project.region.index')->with('error_message', [__('Project region is not deleted')]);
        }
        return redirect()->route('project.region.index')->with('error_message', [__('Wrong ID is provided')]);
    }
}
