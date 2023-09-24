<?php

namespace app\controllers\Auth;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }

    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();
        if($request->isPost())
        {
            $loginForm->loadData($request->getBody());
            if($loginForm->validate() && $loginForm->login())
            {
                $response->redirect('/');
                $request;
            }

        }

        $this->setLayout('auth');

        return $this->reneder('login', [
            'model' => $loginForm
        ]);

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

    public function logout(Request $request , Response $response)
    {

        Application::$app->logout();
        $response->redirect('/');
    }

    public function profile()
    {

        return $this->reneder('profile');
    }

}