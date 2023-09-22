<?php

namespace app\core;
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
            $this->response->setStatusCode(404);
            return $this->renederView("_404");
        }

        if(is_string($callback))
        {
            return $this->renederView($callback);
        }
        if (is_array($callback)){
            Application::$app->controller  = new $callback[0]();
            $callback[0] = Application::$app->controller;
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
        $layout = Application::$app->controller->layout;
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