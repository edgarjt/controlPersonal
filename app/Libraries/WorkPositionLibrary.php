<?php

namespace App\Libraries;

use App\Models\WorkPositionModel;
use Illuminate\Support\Facades\Validator;

class WorkPositionLibrary
{
    public function getWorkPosition() {
        try {
            $WorkPosition = WorkPositionModel::all();

            if (is_null($WorkPosition))
                return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

            return $WorkPosition;

        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function addWorkPosition($request) {
        try {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'name' => 'required',
                'code' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);
            }

            $WorkPosition = new WorkPositionModel();
            $WorkPosition->area = $request->area;
            $WorkPosition->name = $request->name;
            $WorkPosition->description = $request->description;
            $WorkPosition->code = $request->code;
            $WorkPosition->status = $request->status;
            $WorkPosition->save();

            if (!is_null($WorkPosition))
                return response()->json(['status' => true, 'message' => 'Datos guardados correctamente', 'data'=>$WorkPosition]);

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al guardar los datos'],404);
        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }

    }

    public function deleteWorkPosition($request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos','error' => $validator->errors()], 404);
            }

            $WorkPosition = WorkPositionModel::where('id', $request->id)->first();

            if (is_null($WorkPosition))
                return response()->json(['status' => false, 'message' => 'Cargo no encontrado'], 404);

            $WorkPosition->delete();

            if (!is_null($WorkPosition))
                return response()->json(['status' => true, 'message' => 'Datos Eliminados correctamente', 'data' => $WorkPosition]);

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'],404);

        } catch (\Throwable $e) {

            logger($e);
            return response()->json(['status' => true, 'message' => 'Internal Server error'],500);
        }
    }

    public function updateWorkPosition($request) {
        try {
            $validator = Validator::make($request->all(), [
                'area' => 'required',
                'name' => 'required',
                'code' => 'required',
                'status' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'Datos inválidos', 'error' => $validator->errors()], 404);
            }

            $WorkPosition = WorkPositionModel::where('id', $request->id)->first();

            if (is_null($WorkPosition))
                return response()->json(['status' => false, 'message' => 'Dato no encontrado'],404);

            $WorkPosition->fill($request->all());
            $WorkPosition->update();

            if (!is_null($WorkPosition)) {
                return response()->json(['status' => true, 'message' => 'Datos actualizados correctamente', 'data' => $WorkPosition]);
            }

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al guardar los datos'], 404);

        } catch (\Throwable $e) {
            logger($e);
            return response()->json(['status' => false, 'message' => 'Internal server error', 'code' => 500], 500);
        }
    }
}
