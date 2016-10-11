<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\bootstrap\Widget;
use yii\widgets\ActiveForm;
use unclead\multipleinput\renderers\TableRenderer;
use unclead\multipleinput\renderers\RendererInterface;

/**
 * Class TabularInput
 * @package unclead\multipleinput
 */
class TabularInput extends Widget
{
    const POS_HEADER    = RendererInterface::POS_HEADER;
    const POS_ROW       = RendererInterface::POS_ROW;
    const POS_FOOTER    = RendererInterface::POS_FOOTER;

    /**
     * @var array
     */
    public $columns = [];

    /**
     * @var integer maximum number of rows
     */
    public $max;

    /**
     * @var int minimum number of rows
     */
    public $min;

    /**
     * @var array client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when
     * you use widget without a model, since in this case widget is not able to detect client-side options
     * automatically.
     */
    public $attributeOptions = [];

    /**
     * @var array the HTML options for the `remove` button
     */
    public $removeButtonOptions;

    /**
     * @var array the HTML options for the `add` button
     */
    public $addButtonOptions;

    /**
     * @var bool whether to allow the empty list
     */
    public $allowEmptyList = false;

    /**
     * @var Model[]|ActiveRecordInterface[]
     */
    public $models;

    /**
     * @var string|array position of add button.
     */
    public $addButtonPosition;

    /**
     * @var array|\Closure the HTML attributes for the table body rows. This can be either an array
     * specifying the common HTML attributes for all body rows, or an anonymous function that
     * returns an array of the HTML attributes. It should have the following signature:
     *
     * ```php
     * function ($model, $index, $context)
     * ```
     *
     * - `$model`: the current data model being rendered
     * - `$index`: the zero-based index of the data model in the model array
     * - `$context`: the TabularInput widget object
     *
     */
    public $rowOptions = [];


    /**
     * @var string the name of column class. You can specify your own class to extend base functionality.
     * Defaults to `unclead\multipleinput\TabularColumn`
     */
    public $columnClass;

    /**
     * @var string the name of renderer class. Defaults to `unclead\multipleinput\renderers\TableRenderer`.
     * @since 1.4
     */
    public $rendererClass;

    /**
     * @var ActiveForm an instance of ActiveForm which you have to pass in case of using client validation
     * @since 2.1
     */
    public $form;

    /**
     * Initialization.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        if (empty($this->models)) {
            throw new InvalidConfigException('You must specify "models"');
        }

        if ($this->form !== null && !$this->form instanceof ActiveForm) {
            throw new InvalidConfigException('Property "form" must be an instance of yii\widgets\ActiveForm');
        }

        foreach ($this->models as $model) {
            if (!$model instanceof Model) {
                throw new InvalidConfigException('Model has to be an instance of yii\base\Model');
            }
        }

        parent::init();
    }

    /**
     * Run widget.
     */
    public function run()
    {
        return $this->createRenderer()->render();
    }

    /**
     * @return TableRenderer
     */
    private function createRenderer()
    {
        $config = [
            'id'                => $this->options['id'],
            'columns'           => $this->columns,
            'min'               => $this->min,
            'max'               => $this->max,
            'attributeOptions'  => $this->attributeOptions,
            'data'              => $this->models,
            'columnClass'       => $this->columnClass !== null ? $this->columnClass : TabularColumn::className(),
            'allowEmptyList'    => $this->allowEmptyList,
            'rowOptions'        => $this->rowOptions,
            'addButtonPosition' => $this->addButtonPosition,
            'context'           => $this,
            'form'              => $this->form
        ];

        if ($this->removeButtonOptions !== null) {
            $config['removeButtonOptions'] = $this->removeButtonOptions;
        }

        if ($this->addButtonOptions !== null) {
            $config['addButtonOptions'] = $this->addButtonOptions;
        }

        if (!$this->rendererClass) {
            $this->rendererClass = TableRenderer::className();
        }

        $config['class'] = $this->rendererClass ?: TableRenderer::className();

        return Yii::createObject($config);
    }
}