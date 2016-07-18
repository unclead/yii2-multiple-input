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
            $index = '{' . $this->renderer->getIndexPlaceholder() . '}';
        }
        
        $elementName = $this->isRendererHasOneColumn()
            ? '[' . $this->name . '][' . $index . ']'
            : '[' . $index . '][' . $this->name . ']';

        $prefix = $withPrefix ? $this->getInputNamePrefix() : '';
        
        return  $prefix . $elementName;
    }

    /**
     * @return bool
     */
    private function isRendererHasOneColumn()
    {
        return count($this->renderer->columns) === 1; 
    }

    /**
     * Return prefix for name of input.
     *
     * @return string
     */
    protected function getInputNamePrefix()
    {
        $model = $this->context->model;
        if ($model instanceof Model) {
            if (empty($this->renderer->columns) || ($this->isRendererHasOneColumn() && $this->hasModelAttribute($this->name))) {
                return $model->formName();
            }
            
            return Html::getInputName($this->context->model, $this->context->attribute);
        }
        
        return $this->context->name;
    }

    protected function hasModelAttribute($name)
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

    /**
     * @param int|string|null $index
     * @return null|string
     */
    public function getFirstError($index)
    {
        if ($index === null) {
            return null;
        }
        
        if ($this->isRendererHasOneColumn()) {
            $attribute = $this->name . '[' . $index . ']';
        } else {
            $attribute = $this->context->attribute . $this->getElementName($index, false);
        }

        $model = $this->context->model;
        if ($model instanceof Model) {
            return $model->getFirstError($attribute);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    protected function renderWidget($type, $name, $value, $options)
    {
        // Extend options in case of rendering embedded MultipleInput
        // We have to pass to the widget an original model and an attribute to be able get a first error from model
        // for embedded widget.
        if ($type === MultipleInput::className() && strpos($name, $this->renderer->getIndexPlaceholder()) === false) {
            $model = $this->context->model;

            // in case of embedding level 2 and more
            if (preg_match('/^([\w\.]+)(\[.*)$/', $this->context->attribute, $matches)) {
                $search = sprintf('%s[%s]%s', $model->formName(), $matches[1], $matches[2]);
            } else {
                $search = sprintf('%s[%s]', $model->formName(), $this->context->attribute);
            }

            $replace = $this->context->attribute;

            $attribute = str_replace($search, $replace, $name);

            $options['model'] = $model;
            $options['attribute'] = $attribute;
        }

        return parent::renderWidget($type, $name, $value, $options);
    }
}