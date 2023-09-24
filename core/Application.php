<?php

namespace app\core;


use app\core\db\Database;
use app\core\db\DbModel;

class Application
{
    public static string $ROOT_DIR;

    public string $layout = 'main';
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public Session $session;
    public static Application $app;
    public ?Controller $controller = null;
    public ?UserModel $user;
    public View $view;


    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->userClass = $config['userClass'];
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
        $this->view = new View;


        $primaryValue = $this->session->get('user');
        if($primaryValue) {
            $primaryKey = (new $this->userClass)->primaryKey();
            $this->user = (new $this->userClass)->findOne([$primaryKey => $primaryValue]);
        }else
        {
            $this->user = null ;
        }
    }

    public function run(): void
    {

        try {
            echo $this->router->resolve();

        }catch (\Exception $e){
            $this->response->setStatusCode($e->getCode());
            echo $this->view->renederView('_error', [
                'exception' => $e
            ]);
        }

    }

    /**
     * @return Application
     */
    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;

    }

    public static function isGuest()
    {

        return !self::$app->user;
    }

    public function logout()
    {
        $this->user = null;
       $this->session->remove('user');

    }
}