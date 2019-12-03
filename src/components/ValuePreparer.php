<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 12/3/19
 * Time: 11:37 AM
 */

namespace unclead\multipleinput\components;


use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;

class ValuePreparer
{
    protected $model = null;
    protected $name = null;
    protected $defaultValue = null;
    public function __construct($name = null, $defaultValue = null)
    {
        $this->name = $name;
        $this->defaultValue = $defaultValue;
    }

    public function prepare($data, $contextParams = [])
    {
        $value = null;
        if ($data instanceof ActiveRecordInterface) {
            $relation = $data->getRelation($this->name, false);
            if ($relation !== null) {
                $value = $relation->findFor($this->name, $data);
            } else if ($data->hasAttribute($this->name)) {
                $value = $data->getAttribute($this->name);
            } else {
                $value = $data->{$this->name};
            }
        } else if ($data instanceof Model) {
            $value = $data->{$this->name};
        } else
      if (is_array($data)) {
            $value = ArrayHelper::getValue($data, $this->name, null);
        } else if(is_string($data) || is_numeric($data)) {
            $value = $data;
        }
//
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