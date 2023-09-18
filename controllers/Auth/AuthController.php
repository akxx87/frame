<?php

namespace app\controllers\Auth;

use app\core\Controller;
use app\core\Request;
use app\models\RegisterModel;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $this->setLayout('auth');

        return $this->reneder('login');

    }

    public function register(Request $request)
    {

        $registermodel = new RegisterModel();

        if($request->isPost())
        {

            $registermodel->loadData($request->getBody());


            if($registermodel->validate() && $registermodel->register())
            {
                return 'Success';
            }

            var_dump($registermodel->errors);

            return $this->reneder('register', [

                'model' => $registermodel
            ]);

        }
        $this->setLayout('auth');

        return $this->reneder('register', [

            'model' => $registermodel
        ]);

    }
}