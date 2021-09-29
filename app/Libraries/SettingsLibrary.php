<?php

namespace App\Libraries;

use App\Models\SettingModel;
use Illuminate\Support\Facades\Validator;

class SettingsLibrary
{
    public function getSetting($code) {
        try {
            $setting = SettingModel::where('code', $code)->first();

            if (is_null($setting))
                return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado'], 404);

            return $setting;

        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }

    public function updateForm($request) {
        try {
            $validator = Validator::make($request->all(), [
                'code' => 'required',
                'value' => 'required',
            ]);

            if ($validator->fails())
                return response()->json(['status' => false, 'message' => 'Datos inválidos', 'error' => $validator->errors()], 404);

            $setting = SettingModel::where('code', $request->code)->first();

            if (is_null($setting))
                return response()->json(['status' => false, 'message' => 'Dato no encontrado'],404);

            $setting->fill($request->all());
            $setting->update();

            if (!is_null($setting))
                return response()->json(['status' => true, 'message' => 'Datos actualizados correctamente', 'data' => $setting]);

            return response()->json(['status' => false, 'message' => 'Ocurrio un error inesperado al guardar los datos'], 404);

        } catch (\Throwable $e) {
            logger($e);
            return  response()->json(['status' => false, 'message' => 'Internal server error', 'code'=>500], 500);
        }
    }
}
