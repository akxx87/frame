<?php

namespace app\core;

abstract class Model
{

    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX= 'max';
    public const RULE_MATCH= 'match';
    public const RULE_UNIQUE= 'unique';
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


    public function labels(): array
    {

        return [];
    }

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
                        $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                    }
                    if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL))
                    {
                        $this->addErrorForRule($attribute, self::RULE_EMAIL);
                    }
                    if($ruleName === self::RULE_MIN && strlen($value) < $rule['min'])
                    {
                        $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                    }
                    if($ruleName === self::RULE_MAX && strlen($value) > $rule['max'])
                    {
                        $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                    }
                    if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']})
                    {
                        $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                    }
                    if($ruleName === self::RULE_UNIQUE)
                    {
                        $className = $rule['class'];
                        $uniquwatribute = $rule['attribute'] ?? $attribute;
                        $tableName = $className::tableName();
                        $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniquwatribute = :attr");
                        $statement->bindValue(':attr', $value);
                        $statement->execute();

                        $record =  $statement->fetchObject();
                        if($record)
                        {
                            $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);

                        }

                    }
            }
        }

        return empty($this->errors);
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    private function addErrorForRule(string $attribute, string $rules, $params = [])
    {

            $message = $this->errorMesseges()[$rules] ?? '';

            foreach ($params as $key => $param)
            {
                $message = str_replace("{{$key}}", $param, $message);

            }

            $this->errors[$attribute][] =$message;

    }

    public function addError(string $attribute, string $message)
    {


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
            self::RULE_UNIQUE => '{field} is alredy used',

        ];

    }

    public function hasError($atrbute)
    {

        return $this->errors[$atrbute] ?? false;


    }

    public function getFirstError($atrbute)
    {
        return $this->errors[$atrbute][0] ?? false;

    }

}