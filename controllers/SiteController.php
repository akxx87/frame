<?php

namespace app\controllers;
use app\core\Application;
/**
 *
 * @package qpp\controllers
 */

class SiteController
{

    public function home()
    {

      $params = [
          'name' => "codeholic"
      ];
        return Application::$app->router->renederView('home', $params);
    }

    public function action()
    {

        return 'handlig data gfggg';

    }


    public function contact()
    {

        return Application::$app->router->renederView('contact');

    }


}