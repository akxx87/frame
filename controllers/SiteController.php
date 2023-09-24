<?php

namespace app\controllers;
use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\ContactForm;
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


    public function contact(Request $request , Response $response)
    {

        $contact = new ContactForm();
        if($request ->isPost())
        {
            $contact->loadData($request->getBody());
            if($contact->validate() && $contact->send())
                {
                    Application::$app->session->setFlash('success', 'thanks for contact');
                    return $response->redirect('/contact');
                }

        }

        return $this->reneder('contact', [

            'model' => $contact

        ]);

    }


}