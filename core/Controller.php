<?php

namespace app\core;

use app\core\middlewares\BaseMiddeware;

class Controller
{


    public string $layout = 'main';
    public string $action = '';
    /**
     * @var BaseMiddeware[]
     */
    protected array $middlewares =[] ;



    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function reneder($view , $params = [])
    {

        return Application::$app->view->renederView($view , $params);
    }


    public function registerMiddleware(BaseMiddeware $middeware)
    {

        $this->middlewares[] = $middeware;

    }

    /**
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }


}