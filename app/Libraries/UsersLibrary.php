<?php


namespace App\Libraries;


use App\Constants\UserConstant;
use App\Mail\SendPasswordMail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UsersLibrary
{
    public function getUsers() {
        try {
            $users = User::with('role')->with('workPosition')->get();

            if (is_null($users))
                return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

            return $users;

        } catch (\Throwable $e) {
            logger('Get users' . $e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function addUser($request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'first_surname' => 'required',
                'last_surname' => 'required',
                'nacimiento' => 'required',
                'curp' => 'required|unique:users',
                'rfc' => 'required|unique:users',
                'street' => 'required',
                'betweenStreet' => 'required',
                'city' => 'required',
                'cp' => 'required',
                'genero' => 'required',
                'date' => 'required',
                'dep' => 'required',
                'depa' => 'required',
                'cargo' => 'required',
                'email' => 'required|unique:users',
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);
            }

            if (is_null($request->password) || !$request->password || $request->password == '') {
                $password = Str::random(5);
                $email = $request->email;
                $role = 2;

                Mail::to($request->email)->send(new SendPasswordMail($password));
            } else {
                $password = $request->password;
                $role = $request->role;
            }

            $user = new User;
            $user->name = $request->name;
            $user->first_surname = $request->first_surname;
            $user->last_surname = $request->last_surname;
            $user->nacimiento = $request->nacimiento;
            $user->curp = $request->curp;
            $user->rfc = $request->rfc;
            $user->state = $request->state;
            $user->street = $request->street;
            $user->betweenStreet = $request->betweenStreet;
            $user->city = $request->city;
            $user->cp = $request->cp;
            $user->genero = $request->genero;
            $user->date = $request->date;
            $user->dep = $request->dep;
            $user->depa = $request->depa;
            $user->cargo = $request->cargo;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->boss = $request->boss;
            $user->password = Hash::make($password);
            $user->theme = UserConstant::THEME;
            $user->role_id = $role;
            $user->save();

            if (!is_null($user)){
                $userWithRole = User::where('id', $user->id)->with('role')->with('workPosition')->first();
                return response()->json(['status' => true, 'message' => 'Datos guardados correctamente', 'data' => $userWithRole, 'password' => $password]);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al guardar los datos'],404);
        } catch (\Throwable $e) {
            logger('Add user' . $e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }

    }

    public function deleteUser($request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);
            }

            $user = User::where('id', $request->id)->with('role')->first();

            if (is_null($user))
                return response()->json(['status' => false, 'message' => 'Usuario no encontrado'], 404);

            $user->delete();

            if (!is_null($user))
                return response()->json(['status' => true, 'message' => 'Datos Eliminados correctamente', 'data' => $user]);

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'],404);

        } catch (\Throwable $e) {

            logger('Deleted user: ' . $e);
            return response()->json(['status' => true, 'message' => 'Internal Server error'],500);
        }
    }

    public function updateUser($request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'first_surname' => 'required',
                'last_surname' => 'required',
                'email' => "required|unique:users,email,{$request->id}",
                'rfc' => 'required',
                'role_id' => 'required',
                'work_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);
            }

            $user = User::where('id', $request->id)->first();

            if (is_null($user))
                return response()->json(['status' => false, 'message' => 'Usuario no encontrado'], 404);

            $user->fill($request->all());
            $user->update();

            if (!is_null($user)){
                $userWithRole = User::where('id', $user->id)->with('role')->with('workPosition')->first();
                return response()->json(['status' => true, 'message' => 'Datos actualizados correctamente', 'data'=>$userWithRole]);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrió un error inesperado al guardar los datos'],404);
        } catch (\Throwable $e) {
            logger('Update user' . $e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }

    }

    public function theme($request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'theme' => 'required',
            ]);

            if ($validator->fails())
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);

            $user = User::where('id', $request->id)->first();

            if (is_null($user))
                return response()->json(['status' => false, 'message' => 'Usuario no encontrado'], 404);

            $user->fill($request->all());
            $user->update();

            if (!is_null($user)){
                return response()->json(['status' => true, 'message' => 'Theme modified']);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrió un error inesperado al guardar los datos'],404);

        } catch (\Throwable $e) {
            logger('Update user' . $e);

        }
    }

}
