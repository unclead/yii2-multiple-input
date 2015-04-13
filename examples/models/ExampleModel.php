<?php

namespace unclead\widgets\examples\models;

use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;

/**
 * Class ExampleModel
 * @package unclead\widgets\examples\actions
 */
class ExampleModel extends Model
{
    const TYPE_EMAIL = 'email';
    const TYPE_PHONE = 'phone';

    public $emails;

    public $phones;


    public function rules()
    {
        return [
            ['emails', 'validateEmails'],
            ['phones', 'validatePhones']
        ];
    }

    public function attributes()
    {
        return [
            'emails',
            'phones'
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['emails', 'phones']
        ];
    }

    public function validatePhones($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        $multiple = true;
        if(!is_array($items)) {
            $multiple = false;
            $items = (array) $items;
        }

        foreach ($items as $index => $item) {
            $validator = new NumberValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . ($multiple ? '[' . $index . ']' : '');
                $this->addError($key, $error);
            }
        }
    }

    public function validateEmails($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        $multiple = true;
        if(!is_array($items)) {
            $multiple = false;
            $items = (array) $items;
        }

        foreach ($items as $index => $item) {
            $validator = new EmailValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . ($multiple ? '[' . $index . ']' : '');
                $this->addError($key, $error);
            }
        }
    }
}