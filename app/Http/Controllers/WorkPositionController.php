<?php

namespace App\Http\Controllers;

use App\Libraries\WorkPositionLibrary;
use Illuminate\Http\Request;

class WorkPositionController extends Controller
{
    protected $workPositionLibrary;

    public function __construct(WorkPositionLibrary $workPositionLibrary)
    {
        $this->workPositionLibrary = $workPositionLibrary;
    }

    /**
     * @return mixed
     */
    public function getWorksPosition() {
        return $this->workPositionLibrary->getWorkPosition();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addWorkPosition(Request $request) {
        return $this->workPositionLibrary->addWorkPosition($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteWorkPosition(Request $request) {
        return $this->workPositionLibrary->deleteWorkPosition($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateWorkPosition(Request $request) {
        return $this->workPositionLibrary->updateWorkPosition($request);
    }
}
