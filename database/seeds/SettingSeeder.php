<?php

use App\Models\SettingModel;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = SettingModel::where('code', 'register')->first();

        if (is_null($setting)) {
            SettingModel::create([
                'code' => 'register',
                'description' => 'Activa el formulario de registro para el usuario sin privilegios',
                'title' => 'Formulario de registro',
                'value' => 0
            ]);
        }
    }
}
