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
        $method = $this->request->getMethod();
        $callback = $this->routers[$method][$path] ?? false;

        if($callback === false)
        {
            return "not fund";
        }

        if(is_string($callback))
        {
            return $this->renederView($callback);
        }
        call_user_func($callback);

    }

    public function renederView($view)
    {

        include_once __DIR__."/../views/$view.php";

    }

}