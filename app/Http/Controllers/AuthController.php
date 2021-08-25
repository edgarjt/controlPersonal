<?php

namespace App\Http\Controllers;

use App\Libraries\AuthLibrary;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    protected $authLibrary;

    /**
     * AuthController constructor.
     * @param AuthLibrary $authLibrary
     */
    public function __construct(AuthLibrary $authLibrary) {
        $this->authLibrary = $authLibrary;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        return $this->authLibrary->loginLibrary($request);
    }

    /*
     * @logout user
     */
    public function logout()
    {
        $this->authLibrary->logoutLibrary();
    }

    /**
     * @Refresh Token
     */
    public function refresh()
    {
        $this->authLibrary->refreshLibrary();
    }
}
