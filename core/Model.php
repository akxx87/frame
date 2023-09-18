<?php

namespace app\core;

abstract class Model
{

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX= 'max';
    public const RULE_MATCH= 'matche';
   /* public const RULE_REQUIRED = 'required';*/


    public function loadData($data)
    {
        foreach ($data as $key => $datum)
        {
            if(property_exists($this, $key))
            {
                $this->{$key} = $datum;

            }
        }
    }

    abstract public function rules(): array;

    public array $errors = [];

    public function validate()
    {

        foreach ($this->rules() as $attribute => $rules)
        {
            $value = $this->{$attribute};
            foreach ($rules as $rule)
            {
                $ruleName = $rule;
                    if(!is_string($ruleName))
                    {
                        $ruleName = $rule[0];
                    }
                    if($ruleName === self::RULE_REQUIRED && !$value)
                    {
                        $this->addError($attribute, self::RULE_REQUIRED);
                    }
                    if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    {
                        $this->addError($attribute, self::RULE_EMAIL);
                    }
                    if($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    {
                        $this->addError($attribute, self::RULE_MIN);
                    }
                    if($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    {
                        $this->addError($attribute, self::RULE_MAX, $rule);
                    }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $rules, $params = [])
    {

            $message = $this->errorMesseges()[$rules] ?? '';

            $this->errors[$attribute][] =$message;

    }

    public function errorMesseges()
    {

        return [

            self::RULE_REQUIRED => 'this is required',
            self::RULE_EMAIL => 'email must been required',
            self::RULE_MIN => 'this is required min {min}',
            self::RULE_MAX => 'this is required max {max}',
            self::RULE_MATCH => 'this is required {match}',

        ];

    }

}