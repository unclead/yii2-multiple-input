<?php

namespace unclead\widgets\examples\models;

use yii\base\Model;
use yii\validators\EmailValidator;
use yii\validators\NumberValidator;
use yii\validators\RequiredValidator;

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
     * @var array
     */
    public $phones;

    /**
     * @var array
     */
    public $schedule;

    /**
     * @var bool
     */
    public $enable;

    /**
     * @var string
     */
    public $title;

    public function init()
    {
        parent::init();
        $this->emails = [
            'test@test.com',
            'test2@test.com',
            'test3@test.com',
        ];

        $this->schedule = [
            [
                'day'       => '27.02.2015',
                'user_id'   => 31,
                'priority'  => 1,
                'enable'    => 1
            ],
            [
                'day'       => '27.02.2015',
                'user_id'   => 33,
                'priority'  => 2,
                'enable'    => 0
            ],
        ];
    }


    public function rules()
    {
        return [
            ['title', 'required'],
            ['emails', 'validateEmails'],
            ['phones', 'validatePhones'],
            ['schedule', 'validateSchedule']
        ];
    }

    public function attributes()
    {
        return [
            'emails',
            'phones',
            'title',
            'schedule'
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['emails', 'phones', 'schedule', 'title']
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

        foreach ($items as $index => $item) {
            $validator = new EmailValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, $error);
            }
        }
    }

    public function validateSchedule($attribute)
    {
        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) {
            $error = null;
            foreach (['user_id', 'priority'] as $name) {
                $error = null;
                $value = isset($row[$name]) ? $row[$name] : null;
                $requiredValidator->validate($value, $error);
                if (!empty($error)) {
                    $key = $attribute . '[' . $index . '][' . $name . ']';
                    $this->addError($key, $error);
                }
            }
        }
    }
}