<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput\components;


use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class ValuePreparer.
 *
 * @package unclead\multipleinput\components
 */
class ValuePreparer
{
    /**
     * @var string Key of prepared attribute
     */
    protected $name = null;

    /**
     * @var mixed default value
     */
    protected $defaultValue = null;

    /**
     * ValuePreparer constructor.
     * @param string|null $name
     * @param mixed|null $defaultValue
     */
    public function __construct($name = null, $defaultValue = null)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @param $data Prepared data
     *
     * @return int|mixed|null|string
     */
    public function prepare($data)
    {
        $value = null;
        if ($data instanceof ActiveRecordInterface) {
            if ($data->canGetProperty($this->name)) {
                $value = $data->{$this->name};
            } else {
                $relation = $data->getRelation($this->name, false);
                if ($relation !== null) {
                    $value = $relation->findFor($this->name, $data);
                } else {
                    $value = $data->{$this->name};
                }
            }
        } elseif ($data instanceof Model) {
            $value = $data->{$this->name};
        } elseif (is_array($data)) {
            $value = ArrayHelper::getValue($data, $this->name, null);
        } elseif(is_string($data) || is_numeric($data)) {
            $value = $data;
        }

        if ($this->defaultValue !== null && $this->isEmpty($value)) {
            $value = $this->defaultValue;
        }

        return $value;
    }

    protected function isEmpty($value)
    {
        return $value === null || $value === [] || $value === '';
    }
}