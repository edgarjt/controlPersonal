<?php

namespace App\Http\Controllers;

use App\Libraries\UsersLibrary;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $userLibrary;

    /**
     * UserController constructor.
     * @param UsersLibrary $usersLibrary
     */
    public function __construct(UsersLibrary $usersLibrary) {
        $this->userLibrary = $usersLibrary;
    }

    /**
     * @return \App\User[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUsers() {
        return $this->userLibrary->getUsers();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUser(Request $request) {
        return $this->userLibrary->addUser($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request) {
        return $this->userLibrary->deleteUser($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request) {
        return $this->userLibrary->updateUser($request);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function theme(Request $request) {
        return $this->userLibrary->theme($request);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(){
        return $this->userLibrary->resetPassword();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProfile(Request $request) {
        return $this->userLibrary->addProfile($request);
    }

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function getProfile($id, $type) {
        return $this->userLibrary->getProfile($id, $type);
    }

    public function deleteProfile($id) {
        return $this->userLibrary->deleteProfile($id);
    }
}
