<?php

namespace app\core\exception;

class ForbiddenException extends \Exception
{

    protected $message = 'You dont not perrmision to acces this page';
    protected $code = 403;

}