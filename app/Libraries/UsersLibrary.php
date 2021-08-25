<?php


namespace App\Libraries;


use App\Constants\UserConstant;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersLibrary
{
    public function getUsers() {
        try {
            $users = User::with('role')->get();

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
                'email' => 'required|unique:users',
                'password' => 'required',
                'role_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);
            }

            $user = new User;
            $user->name = $request->name;
            $user->first_surname = $request->first_surname;
            $user->last_surname = $request->last_surname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->theme = UserConstant::THEME;
            $user->role_id = $request->role_id;
            $user->save();

            if (!is_null($user)){
                $userWithRole = User::where('id', $user->id)->with('role')->first();
                return response()->json(['status' => true, 'message' => 'Datos guardados correctamente', 'data'=>$userWithRole]);
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
            return response()->json(['status' => true, 'message' => 'Internal Server error', 'data' => $user],500);
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
                'role_id' => 'required'
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
                $userWithRole = User::where('id', $user->id)->with('role')->first();
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
