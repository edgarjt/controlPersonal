<?php

namespace App\Libraries;

use App\Models\CatRoleModel;
use Illuminate\Support\Facades\Validator;

class RolesLibrary
{
    public function getRoles() {
        try {
            $roles = CatRoleModel::all();

            if (is_null($roles))
                return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

            return $roles;

        } catch (\Throwable $e) {
            logger('get roles: ' . $e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function addRole($request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'code' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos', 'error' => $validator->errors()], 404);
            }

            $CatRole = new CatRoleModel();
            $CatRole->name = $request->name;
            $CatRole->code = $request->code;
            $CatRole->description = $request->description;
            $CatRole->save();

            if (!is_null($CatRole)) {
                return response()->json(['status' => true, 'message' => 'Datos guardados correctamente', 'data' => $CatRole]);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al guardar los datos'], 404);

        } catch (\Throwable $e) {
            logger('Add role: ' . $e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }

    public function deleteRole($request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos', 'error' => $validator->errors()], 404);
            }

            $CatRole = CatRoleModel::where('id', $request->id)->first();

            $CatRole->delete();

            if (!is_null($CatRole)) {
                return response()->json(['status' => true, 'message' => 'Datos eliminado correctamente', 'data' => $CatRole]);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al eliminar los datos'], 404);

        } catch (\Throwable $e) {
            logger('Delete role: ' . $e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }

    public function updateRole($request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'code' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos', 'error' => $validator->errors()], 404);
            }

            $CatRole = CatRoleModel::where('id', $request->id)->first();

            if (is_null($CatRole))
                return response()->json(['status' => false, 'message' => 'El rol no se encontro'],404);

            $CatRole->fill($request->all());
            $CatRole->update();

            if (!is_null($CatRole)) {
                return response()->json(['status' => true, 'message' => 'Datos actualizados correctamente', 'data' => $CatRole]);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al guardar los datos'], 404);

        } catch (\Throwable $e) {
            logger('Update role: ' . $e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }
}
