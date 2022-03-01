<?php

use Illuminate\Database\Seeder;
use App\Project;
use App\QueryGroup;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('positions')->truncate();

        // Project::all()->each(function ($project){
        //     $queryGroups = QueryGroup::where('project_id', '>=', $project->id)->orderBy('parent_group_id', 'asc')->get();
        //     $projectQueries = QueryGroup::getAllChildren( $queryGroups, $project->queryGroup->id );
        //     $groupQueryIDs = [];
        //     foreach ($projectQueries as $key => $groupQuery) {
        //         $groupQueryIDs[] = $groupQuery['id'];
        //     }
        //     $queries =  DB::table('queries')
        //     ->select(DB::raw("queries.name as query_name, queries.id as query_id"))
        //     ->whereIn('queries.query_group_id', $groupQueryIDs)
        //     ->orderBy('queries.name', 'desc')
        //     ->get();
        //     foreach ($queries as $key => $query) {
        //         if (rand(1, 5) == 1) {
        //             factory(App\Position::class, 10)->create(['project_id'=> $project->id, 'query_id'=> $query->query_id]); 
        //         }
        //     }
        // });
    }
}
