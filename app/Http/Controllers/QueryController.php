<?php

namespace App\Http\Controllers;

use App\Query;
use App\QueryGroup;
use App\Region;
use App\support\Query\ImportQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $queries = Query::with('group')->orderBy('query_group_id', 'desc')->paginate( config('app.paginate_by', '25') )->onEachSide(2);

        return view('query.index', [
            'items'=> $queries
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Http\Response
     */
    public function list($query_group_id = 0)
    {
        $queries = ( $query_group_id == 0 && session('selected_project_id') !== null ) ?
            Query
                ::select(['queries.id', 'queries.name', 'queries.is_active', 'regions.name as region_name'])
                ->join('query_groups', 'query_groups.id', 'queries.query_group_id')
                ->leftJoin('regions', 'regions.id', 'queries.region_id')
                ->where('query_groups.project_id', session('selected_project_id'))
                ->orderBy('queries.id', 'asc')->get() :
            Query
                ::select(['queries.id', 'queries.name', 'queries.is_active', 'regions.name as region_name'])
                ->leftJoin('regions', 'regions.id', 'queries.region_id')
                ->where(['query_group_id' => $query_group_id])
                ->orderBy('queries.is_active', 'desc')
                ->orderBy('queries.id', 'asc')
                ->get();
        // echo Query::with('region:id,name')
        //         ->join('query_groups', 'query_groups.id', 'queries.query_group_id')
        //         ->where('query_groups.project_id', session('selected_project_id'))
        //         ->orderBy('queries.id', 'asc')->toSql();
        return $queries;
    }
    public function fileUploadPost(Request $request)
    {

//        $request->validate([
//            'file' => 'required|mimes:pdf,xlx,csv|max:5000',
//        ]);
        //\File::extension($filename);

        $fileName = time().'.'.$request->file->getClientOriginalExtension();
        $request->file->move(public_path('imports'), $fileName);

        $import = new ImportQuery($fileName);
        return back()
            ->with(['uploaded'=> true,
                'filename'=>$fileName,
                'numberOfQueries'=> $import->numberOfQueries,
                'numberOfGroups'=> $import->numberOfGroups
                ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($query_group_id = 0)
    {
        $queryGroups = QueryGroup::orderBy('parent_group_id', 'asc')->get();
        if (count($queryGroups) == 0) {
            return redirect()->route('query-group.create')->with('error_message', [__('Please create at least one query group')] );
        }
        return view('query.create', [
            'query_group_id'=> $query_group_id,
            'query_groups'=> $queryGroups,
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
        $request->validate([
            'name'=>'required',
            'query_group_id'=>'required|integer',
        ]);

        $query = Query::create(
            $request->all()
        );

        if( !$query ) {
            return redirect()->route('query-group.index')->with('error_message', [__('Query was not created')] );
        }

        return redirect()->route('query-group.index')->with('success_message', [__('Query was created')]);
    }

    /**
     * Rename the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QueryGroup  $queryGroup
     * @return \Illuminate\Http\Response
     */
    public function storeUpdate(Request $request)
    {
        $request->validate([
            'id'=>'required|integer',
            'name'=>'required|string',
            'query_group_id'=>'required|integer',
        ]);

        if ($request->id == '0') {
            $query = Query::create([
                'name'          => $request->name,
                'query_group_id'=> $request->query_group_id,
                'created_at'    => Carbon::now(),
                'updated_at'   => Carbon::now()
            ]);
            return $query->id;
        }
        else {
            $query = Query::find($request->id);
            $query->update($request->all());
            return $query->id;
        }
    }

    /**
     * Rename the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QueryGroup  $queryGroup
     * @return \Illuminate\Http\RedirectResponse
     */
    public function massAssign(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'query_group_id'=>'required|integer',
        ]);
        $result = false;
        if (strlen($request->name) > 1) {
            $names = preg_split("/\r\n|\n|\r/", $request->name);

            $data = array_map(function($name) use ($request){
                return [
                    'name'          => $name,
                    'query_group_id'=> $request->query_group_id,
                    'created_at'    => Carbon::now(),
                    'updated_at'   => Carbon::now()
                ];
            }, $names);
            $result = Query::insert($data);
        }
        return ( $result )
            ? redirect()->back()->with('success_message', [__('Queries were created')] )
            : redirect()->back()->with('error_message', [__('Queries were not created')] );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Query  $query
     * @return \Illuminate\Http\Response
     */
    public function show(Query $query)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Query  $query
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Query $query)
    {
        $queryGroups = QueryGroup
            ::where('project_id', session('selected_project_id'))
            ->orderBy('parent_group_id', 'asc')->get();
        return view('query.edit', [
            'item'=> $query,
            'query_groups'=> $queryGroups,
            'regions'=> Region::all()->sortBy('name')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Query  $query
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Query $query)
    {
        $request->validate([
            'name'=>'required',
            'query_group_id'=>'required|integer',
            'region_id'=>'nullable|integer',
        ]);
        $result = $query->update($request->all());
        return ( $result )
                ? redirect()->route('query-group.index' )->with('success_message', [__('Query is updated')] )
                : redirect()->route('query-group.index' )->with('error_message', [__('Query is not updated')] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRegion(Request $request)
    {
        $request->validate([
            'region_id'=>'required|integer',
        ]);


        $queryIDs = explode(',', $request->query_id);
        $regionID = $request->region_id;

        $allQueries = DB::table('queries')
            ->select(['queries.id', 'queries.name', 'queries.is_active', 'queries.region_id', 'query_groups.project_id'])
            ->join('query_groups', 'query_groups.id', 'queries.query_group_id')
            ->where('query_groups.project_id', session('selected_project_id'))
            ->orderBy('queries.name', 'asc')
            ->get()->toArray();


        $updatingQueries = DB::table('queries')
            ->select(['id', 'name', 'region_id'])
            ->whereIn('id', $queryIDs)
            ->orderBy('name', 'asc')
            ->get()->toArray();

        $duplicateArray = [];
        $changingArray = [];


        foreach ($updatingQueries as $key => $updatingQuery) {
            $hasDuplicate = false;
            $thisQueryID = 0;
            foreach ($allQueries as $key => $singleQuery) {
                if ($singleQuery->id == $updatingQuery->id) {
                    $thisQueryID = $key;
                }

                if ($singleQuery->name == $updatingQuery->name && $singleQuery->region_id == $regionID) {
                    $hasDuplicate = true;
                    $dupArray[] = $updatingQuery->id;
                }


            }

            $changingArray[] = $updatingQuery->id;

            if (!$hasDuplicate && $thisQueryID > 0) {
                $changingArray[] = $updatingQuery->id;
                $allQueries[$thisQueryID]->region_id = $regionID;
            }
            if ($hasDuplicate) {
                $duplicateArray[] = $updatingQuery->id;
            }


        }


        Query::whereIn('id', $duplicateArray)
            ->update(['is_active' => false]);


        $result = Query::whereIn('id', $changingArray)
            ->update(['region_id' => $regionID]);

        return ( $result )
            ? redirect()->back()->with('success_message', [__('Region is assigned')] )
            : redirect()->back()->with('error_message', [__('Region is not assigned')] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function archive(Request $request)
    {
        $queryIDs = explode(',', $request->query_id);
        $result = true;
        foreach ($queryIDs as $queryID) {
            $query = Query::find($queryID);
            $query->is_active = !$query->is_active;
            $result = $result === false ? false : $query->save();
        }
        return ( $result )
            ? redirect()->back()->with('success_message', [__('Query is archived')] )
            : redirect()->back()->with('error_message', [__('Query is not archived')] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $queryIDs = explode(',', $request->query_id);
        $result = true;
        foreach ($queryIDs as $queryID) {
            $result === false ? false : Query::destroy($queryID);
        }
        return ( $result )
            ? redirect()->back()->with('success_message', [__('Query is deleted')] )
            : redirect()->back()->with('error_message', [__('Query is not deleted')] );
    }
}
