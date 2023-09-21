<?php

namespace app\controllers\Auth;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\User;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $this->setLayout('auth');

        return $this->reneder('login');

    }

    public function register(Request $request)
    {

        $user = new User();

        if($request->isPost())
        {

            $user->loadData($request->getBody());


            if($user->validate() && $user->save())
            {
                Application::$app->session->setFlash('success', 'register complead');
                Application::$app->response->redirect('/');
                exit;
            }


            return $this->reneder('register', [

                'model' => $user
            ]);

        }
        $this->setLayout('auth');

        return $this->reneder('register', [

            'model' => $user
        ]);

    }


}