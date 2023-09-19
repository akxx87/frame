<?php

namespace app\core;

class Session
{

    protected const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMesages = $_SESSION[self::FLASH_KEY];
        foreach ($flashMesages as $key => $flashMesage)
        {
                //Mark to be removed

            

        }

    }

    public function setFlash($key , $message)
    {

        $_SESSION[self::FLASH_KEY][$key] = $message;


    }

    public function getFlash($key)
    {


    }
}