<?php

namespace app\core;

class Session
{

    protected const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMesages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMesages as $key => &$flashMesage)
        {
                //Mark to be removed

            $flashMesage['remove'] = true;

        }

        $_SESSION[self::FLASH_KEY] = $flashMesages;

    }

    public function setFlash($key , $message)
    {

        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];


    }

    public function getFlash($key)
    {
      return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;



    }

    public function set($key, $value)
    {

        $_SESSION[$key] = $value;
    }

    public function get($key)
    {

        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {

        unset($_SESSION[$key]);
    }


    public function __destruct()
    {
       $flashMesages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMesages as $key => &$flashMesage)
        {
           if($flashMesage['remove'])
           {
               unset($flashMesages[$key]);
           }
        }

        $_SESSION[self::FLASH_KEY] = $flashMesages;
    }
}