<?php

use Illuminate\Database\Seeder;

class QuerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('queries')->truncate();
        
        factory(App\Query::class, 150)->create();
    }
}
