<?php

namespace app\models;
use app\core\Model;

class RegisterModel extends Model
{

    public string $firstname = '';
    public string $lastname ='';
    public string $email = '';
    public string $password = '';
    public string $confirmpassword = '';

    public function register()
    {

        echo 'new user add';

    }

    public function rules(): array
    {
        return [

            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_EMAIL, self::RULE_REQUIRED],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8 ] , [self::RULE_MAX , 'max' => 20]],
            'confirmpassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],

        ];
    }


}