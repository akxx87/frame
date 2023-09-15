<?php

namespace app\core;
class Router
{
    public Request $request;
    protected  array $routers = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function get($path, $callback)
    {

        $this->routers['get'][$path] = $callback;
    }

    public function resolve()
    {

        $path = $this->request->getPatch();
        $method = $this->routers->getMethod();
        $callback = $this->routers[$method][$path] ?? false;
        if($callback === false)
        {
            echo "not fund";
            exit;
        }

        call_user_func($callback);

    }

    public function getMethod()
    {

        return strtolower($_SERVER['REQUEST_METHOD']);

    }
}