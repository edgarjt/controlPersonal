<?php

use App\Models\WorkPositionModel;
use Illuminate\Database\Seeder;

class WorkPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $work = WorkPositionModel::where('code', 'sistemas')->first();
        if (is_null($work)) {
            WorkPositionModel::create([
                'area' => 'Sistemas',
                'name' => 'Desarrollador web',
                'description' => 'Desarrollador',
                'code' => 'sistemas',
                'status' => 'activo'
            ]);
        }
    }
}
