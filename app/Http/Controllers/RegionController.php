<?php

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('region.index', [
            'items'=> Region::orderBy('name', 'asc')->paginate( config('app.paginate_by', '25') )->onEachSide(2),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('region.create');
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
            'name'=>'required|unique:regions',
        ]);

        $region = Region::create(
            $request->all()
        );

        if( !$region ) {
            return redirect()->route('region.create')->with('error_message', [__('Region was not created')] );
        }

        return redirect()->route('region.create')->with('success_message', [__('Region was created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        return view('region.edit', [
            'item'=> $region
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $result = $region->update($request->all());

        return ( $result )
                ? redirect()->route('region.edit', [ $region] )->with('success_message', [__('Region is updated')] )
                : redirect()->route('region.edit', [ $region] )->with('error_message', [__('Region is not updated')] );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        if (isset($region)) {
            $result = Region::destroy($region->id);
            return ( $result )
                ? redirect()->back()->with('success_message', [__('Region is deleted')] )
                : redirect()->back()->with('error_message', [__('Region is not deleted')] );
        }
        return redirect()->back()->with('error_message', [__('Wrong ID is provided')] );
    }
}
