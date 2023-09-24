<?php

namespace app\core;

class View
{

    public string $title = '';

    public function renederView($view, $params = [])
    {

        $viewContent = $this->renederOnluView($view , $params);
        $layoutContent = $this->layoutContent();

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