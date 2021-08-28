<?php

namespace App\Http\Controllers;

use App\Libraries\RolesLibrary;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    protected $rolesLibrary;

    public function __construct(RolesLibrary $rolesLibrary)
    {
        $this->rolesLibrary = $rolesLibrary;
    }

    /**
     * @return mixed
     */
    public function getRoles() {
        return $this->rolesLibrary->getRoles();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addRole(Request $request) {
        return $this->rolesLibrary->addRole($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function deleteRole(Request $request) {
        return $this->rolesLibrary->deleteRole($request);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateRole(Request $request) {
        return $this->rolesLibrary->updateRole($request);
    }
}
