<?php

namespace app\core;
use app\core\exception\ForbiddenException;
use app\core\exception\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected  array $routers = [];

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }


    public function get($path, $callback)
    {
        $this->routers['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routers['post'][$path] = $callback;
    }

    public function resolve()
    {

        $path = $this->request->getPatch();
        $method = $this->request->method();
        $callback = $this->routers[$method][$path] ?? false;

        if($callback === false)
        {
           throw new NotFoundException();
        }

        if(is_string($callback))
        {
            return $this->renederView($callback);
        }
        if (is_array($callback)){
            /** @ var \app\core\Controller $controller */
           $controller = new $callback[0]();
           Application::$app->controller = $controller;
           $controller->action = $callback[1];
           $callback [0] = $controller;
            foreach ($controller->getMiddlewares() as $middleware)
            {
                $middleware->execute();

            }
        }
        return call_user_func($callback, $this->request, $this->response);

    }

    public function renederView($view, $params = [])
    {

        $layoutContent = $this->layoutContent();
        $viewContent = $this->renederOnluView($view , $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);

    }

    public function renederContent($viewContetn)
    {

        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}', $viewContetn, $layoutContent);

    }

    protected  function layoutContent()
    {
        $layout = Application::$app->layout;
        if(Application::$app->controller->layout) {
            $layout = Application::$app->controller->layout;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();

    }

    protected function renederOnluView($view , $params = [])
    {
        foreach ($params as $key =>  $param)
        {
            $$key = $param;

        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();


    }


}