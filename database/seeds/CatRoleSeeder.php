<?php

use App\Constants\CatRoleConstant;
use App\Models\CatRoleModel;
use Illuminate\Database\Seeder;

class CatRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = CatRoleModel::where('code', CatRoleConstant::ADMIN)->first();
        if (is_null($admin)) {
            CatRoleModel::create([
                'name' => 'Admin del sistema',
                'code' => 'admin',
                'description' => 'Usuario con todos los permisos del sistema'
            ]);
        }

        $user = CatRoleModel::where('code', CatRoleConstant::USER)->first();
        if (is_null($user)) {
            CatRoleModel::create([
                'name' => 'Cuenta de usuario',
                'code' => 'user',
                'description' => 'Usuario con permisos estándar'
            ]);
        }
    }
}
