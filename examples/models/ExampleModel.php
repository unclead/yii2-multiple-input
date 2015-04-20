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

    /**
     * @var array virtual attribute for keeping emails
     */
    public $emails;

    /**
     * @var
     */
    public $phones;

    /**
     * @var
     */
    public $schedule;


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

    /**
     * Phone number validation
     *
     * @param $attribute
     */
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

    /**
     * Email validation.
     *
     * @param $attribute
     */
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