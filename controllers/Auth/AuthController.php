<?php

namespace app\controllers\Auth;

use app\core\Controller;
use app\core\Request;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $this->setLayout('auth');

        return $this->reneder('login');

    }

    public function register(Request $request)
    {

        $this->setLayout('auth');

        if($request->isPost())
        {

            return  'handel sub data';
        }

        return $this->reneder('register');

    }
}