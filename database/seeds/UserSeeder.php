<?php

use App\Models\CatRoleModel;
use App\User;
use App\Constants\CatRoleConstant;
use App\Constants\UserConstant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::where('email', UserConstant::EMAIL)->first();
        $systemRole = CatRoleModel::where('code', CatRoleConstant::ADMIN)->first();

        if (is_null($user) && !is_null($systemRole)) {
            User::create([
                'name' => 'Root',
                'first_surname' => 'Root',
                'last_surname' => 'Root',
                'nacimiento' => '01/06/1997',
                'curp' => '000000000000000000',
                'rfc' => UserConstant::RFC,
                'state' => 'TABASCO',
                'street' => 'root',
                'betweenStreet' => 'root',
                'city' => 'root',
                'cp' => 'root',
                'genero' => 'root',
                'date' => 'root',
                'dep' => 'root',
                'depa' => 'root',
                'cargo' => 'root',
                'email' => UserConstant::EMAIL,
                'phone' => '0000000000',
                'boss' => 'root',
                'password' => Hash::make(UserConstant::PASSWORD),
                'theme' => UserConstant::THEME,
                'role_id' => $systemRole->id
            ]);
        }
    }
}
