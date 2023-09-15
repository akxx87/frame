<?php

namespace app\core;
class Router
{
    public Request $request;
    public Response $response;
    protected  array $routers = [];

    /**
     * @param Request $request
     * @param Request $response
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
        $method = $this->request->getMethod();
        $callback = $this->routers[$method][$path] ?? false;

        if($callback === false)
        {
            $this->response->setStatusCode(404);
            return "not found";
        }

        if(is_string($callback))
        {
            return $this->renederView($callback);
        }
        call_user_func($callback);

    }

    public function renederView($view)
    {

        $layoutContent = $this->layoutContent();
        $viewContent = $this->renederOnluView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);

    }

    protected  function layoutContent()
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();

    }

    protected function renederOnluView($view)
    {
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$view.php";
        return ob_get_clean();


    }


}