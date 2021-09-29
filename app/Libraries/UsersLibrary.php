<?php


namespace App\Libraries;


use App\Constants\UserConstant;
use App\Mail\SendPasswordMail;
use App\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use function PHPUnit\Framework\isNull;

class UsersLibrary
{
    const DIR_PATH = 'userProfile/';
    const LOCAL_DISK = 'local';

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
                'nacimiento' => 'required',
                'curp' => 'required|unique:users',
                'rfc' => 'required|unique:users',
                'state' => 'required',
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

            $verifyPassword = false;

            if (is_null($request->password) || !$request->password || $request->password == '') {
                $password = Str::random(5);
                $role = 2;
                $verifyPassword = true;
            } else {
                $password = $request->password;
                $role = $request->role_id;
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
                $userWithRole = User::where('id', $user->id)->with('role')->first();

                if ($verifyPassword)
                    Mail::to($request->email)->send(new SendPasswordMail($password));

                return response()->json(['status' => true, 'message' => 'Datos guardados correctamente', 'data' => $userWithRole]);
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
            if (!is_null($request->type)) {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'first_surname' => 'required',
                    'last_surname' => 'required',
                    'nacimiento' => 'required',
                    'curp' => "required|unique:users,curp,{$request->id}",
                    'rfc' => "required|unique:users,rfc,{$request->id}",
                    'state' => 'required',
                    'street' => 'required',
                    'betweenStreet' => 'required',
                    'city' => 'required',
                    'cp' => 'required',
                    'genero' => 'required'
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'first_surname' => 'required',
                    'last_surname' => 'required',
                    'nacimiento' => 'required',
                    'curp' => "required|unique:users,curp,{$request->id}",
                    'rfc' => "required|unique:users,rfc,{$request->id}",
                    'state' => 'required',
                    'street' => 'required',
                    'betweenStreet' => 'required',
                    'city' => 'required',
                    'cp' => 'required',
                    'genero' => 'required',
                    'date' => 'required',
                    'dep' => 'required',
                    'depa' => 'required',
                    'cargo' => 'required',
                    'email' => "required|unique:users,email,{$request->id}",
                    'phone' => 'required'
                ]);
            }

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

    public function resetPassword($request){
        try {
            if (is_null($request->type)) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'passwordOld' => 'required',
                    'passwordNew' => 'required'
                ]);
            }else {
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'passwordNew' => 'required'
                ]);
            }


            if ($validator->fails())
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);

            $user = User::where('id', $request->id)->first();

            if (is_null($user))
                return response()->json(['status' => false, 'message' => 'Usuario no encontrado'], 404);

            if (!is_null($request->type)) {
                $new_password = Hash::make($request->passwordNew);
                $user->password = $new_password;
                $user->update();

                if (is_null($user))
                    return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

                return response()->json(['status' => true, 'message' => 'Contraseña actualizada correctamente']);
            }


            $password_verify = Hash::check($request->passwordOld, $user->password);

            if ($password_verify) {
                $new_password = Hash::make($request->passwordNew);

                $user->password = $new_password;
                $user->update();

                if (is_null($user))
                    return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

                return response()->json(['status' => true, 'message' => 'Contraseña actualizada correctamente']);
            }

            return response()->json(['status' => false, 'message' => 'La contraseña es incorrecta'], 406);
        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function addProfile($request) {
        try {
            $validators = Validator::make($request->all(), [
                'id' => 'required',
                'profile' => 'required|image'
            ]);

            if ($validators->fails())
                return response()->json(['status' => false, 'message' => 'Datos invalidos'], 404);

            $file = $request->file('profile');
            $path_original = self::DIR_PATH . "/$request->id/original.png";
            $path = self::DIR_PATH . "/$request->id/profile.png";

            $save_original = Storage::disk(self::LOCAL_DISK)->put($path_original, File::get($file));

            if ($save_original) {
                $image_resize = Image::make($file)->resize(60, 60)->save();
                Storage::disk(self::LOCAL_DISK)->put($path, $image_resize);

                return response()->json(['status' => true, 'message' => 'Imagen guardado']);
            }

            return response()->json(['status' => false, 'message' => 'No se pudo guardar la imagen'], 404);

        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function getProfile($id, $type) {
        try {
            $validators = Validator::make(['id' => $id, 'type' => $type], [
                'id' => 'required|integer',
                'type' => 'required'
            ]);

            if ($validators->fails())
                return response()->json(['status' => false, 'message' => 'Datos invalidos'], 404);

            $exists_file = Storage::disk(self::LOCAL_DISK)->exists(self::DIR_PATH . $id . "/$type.png");

            if ($exists_file){
                $file = Storage::disk(self::LOCAL_DISK)->get(self::DIR_PATH . $id . "/$type.png");
                return Image::make($file)->response('png');
            }


            return response()->json(['status' => false, 'message' => 'No se encontro el archivo'], 404);


        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function deleteProfile($id) {
        try {
            $validators = Validator::make(['id' => $id], [
                'id' => 'required|integer'
            ]);

            if ($validators->fails())
                return response()->json(['status' => false, 'message' => 'Datos invalidos'], 404);

            $exists_dir = Storage::disk(self::LOCAL_DISK)->exists(self::DIR_PATH . $id);

            if ($exists_dir) {
                Storage::disk(self::LOCAL_DISK)->deleteDirectory(self::DIR_PATH . $id);
                return response()->json(['status' => true, 'message' => 'Perfil borrado'], 200);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

}
