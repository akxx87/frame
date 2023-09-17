<?php

namespace app\core;

class Controller
{
    public function reneder($view , $params = [])
    {

        return Application::$app->router->renederView($view , $params);
    }
}