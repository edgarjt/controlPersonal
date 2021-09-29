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
        $this->call(CatRoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WorkPositionSeeder::class);
        $this->call(SettingSeeder::class);
    }
}
