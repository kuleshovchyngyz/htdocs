<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(ProjectRegionSeeder::class);
        $this->call(QueryGroupSeeder::class);
        $this->call(QuerySeeder::class);
        $this->call(PositionSeeder::class);
    }
}
