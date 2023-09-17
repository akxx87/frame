<?php

namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use Illuminate\Support\Facades\App;

/**
 *
 * @package qpp\controllers
 */

class SiteController extends Controller
{

    public function home()
    {

      $params = [
          'name' => "codeholic"
      ];
        return $this->reneder('home', $params);
    }

    public function action(Request $request)
    {

       $body = $request->getBody();

        return 'handlig data gfggg';

    }


    public function contact()
    {

        return $this->reneder('contact');

    }


}