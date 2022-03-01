<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
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



        $client =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if( !$region ) {
            return redirect()->route('region.create')->with('error_message', [__('Region was not created')] );
        }

        return redirect()->route('region.create')->with('success_message', [__('Region was created')]);
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
     * @param Client $client
     * @return void
     */
    public function edit(Client $client)
    {
        return view('clients.edit' , ["client" => $client]);
    }

    public function set_project(Request $request)
    {
        $client = Client::find($request->client);
        $client_projects = $client->projects!="" ? explode(",",$client->projects) : [];
        if($request->checked=="true"){
            if(!in_array($request->project_id,$client_projects)){
                $client_projects[] = $request->project_id;
            }

        }else{
            if(in_array($request->project_id,$client_projects)){
                if (($key = array_search($request->project_id, $client_projects)) !== false) {
                    array_splice($client_projects,$key,1);
                }
            }
        }
        $client->projects = implode(',',$client_projects);
        $client->save();

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
    public function destroy(Client $client)
    {
        $client->user->removeRole('client');
        $client->user->delete();
        $client->delete();
        return redirect()->back();
    }
}
