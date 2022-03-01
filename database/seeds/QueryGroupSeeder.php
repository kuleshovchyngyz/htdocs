<?php

use Illuminate\Database\Seeder;
use App\Project;
use App\QueryGroup;

class QueryGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('query_groups')->truncate();

        Project::all()->each(function ($project){
            factory(App\QueryGroup::class, 5)->create(['project_id'=> $project->id])->each(function ($queryGroup) use ($project) {
                $queryGroup->children()->saveMany(factory(App\QueryGroup::class, 3)->create(['project_id'=> $project->id]));
            });
        });
    }
}
