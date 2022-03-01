<?php

use Illuminate\Database\Seeder;

class ProjectRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::all()->each(function ($project){
            factory(App\Region::class, 5)->create(['project_id'=> $project->id])->each(function ($region) use ($project) {
                $region->children()->saveMany(factory(App\QueryGroup::class, 3)->create(['project_id'=> $project->id]));
            });
        });
    }
}
