<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\Html;
use unclead\widgets\components\BaseColumn;

/**
 * Class MultipleInputColumn
 * @package unclead\widgets
 */
class MultipleInputColumn extends BaseColumn
{
    /**
     * @var MultipleInput
     */
    public $context;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if ($this->enableError && !$this->context->model instanceof Model) {
            throw new InvalidConfigException('Property "enableError" available only when model is defined.');
        }
    }

    /**
     * Returns element's name.
     *
     * @param int|null $index current row index
     * @param bool $withPrefix whether to add prefix.
     * @return string
     */
    public function getElementName($index, $withPrefix = true)
    {
        if (is_null($index)) {
            $index = '{multiple_index}';
        }
        $elementName = count($this->renderer->columns) > 1
            ? '[' . $index . '][' . $this->name . ']'
            : '[' . $this->name . '][' . $index . ']';
        $prefix = $withPrefix ? $this->getInputNamePrefix() : '';
        return  $prefix . $elementName;
    }

    /**
     * Return prefix for name of input.
     *
     * @return string
     */
    private function getInputNamePrefix()
    {
        $model = $this->context->model;
        if ($model instanceof Model) {
            if (empty($this->renderer->columns) || (count($this->renderer->columns) == 1 && $this->hasModelAttribute($this->name))) {
                return $model->formName();
            }
            return Html::getInputName($this->context->model, $this->context->attribute);
        }
        return $this->context->name;
    }

    private function hasModelAttribute($name)
    {
        $model = $this->context->model;

        if ($model->hasProperty($name)) {
            return true;
        } elseif ($model instanceof ActiveRecordInterface && $model->hasAttribute($name)) {
            return true;
        } else {
            return false;
        }
    }

    public function getFirstError($index)
    {
        $attribute = $this->context->attribute . $this->getElementName($index, false);
        return $this->context->model->getFirstError($attribute);
    }
}