<?php

namespace App\Http\Controllers;

use App\Libraries\SettingsLibrary;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settingsLibrary;

    public function __construct(SettingsLibrary $settingsLibrary)
    {
        $this->settingsLibrary = $settingsLibrary;
    }

    public function getSetting($code) {
        return $this->settingsLibrary->getSetting($code);
    }

    public function updateForm(Request $request) {
        return $this->settingsLibrary->updateForm($request);
    }
}
