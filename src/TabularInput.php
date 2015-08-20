<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\bootstrap\Widget;
use unclead\widgets\renderers\TableRenderer;

/**
 * Class TabularInput
 * @package unclead\widgets
 */
class TabularInput extends Widget
{
    /**
     * @var array
     */
    public $columns = [];

    /**
     * @var integer inputs limit
     */
    public $limit;

    /**
     * @var array client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when
     * you use widget without a model, since in this case widget is not able to detect client-side options
     * automatically.
     */
    public $attributeOptions = [];

    /**
     * @var Model[]|ActiveRecord[]
     */
    public $models;


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
        $renderer = $this->createRenderer();
        return $renderer->render();
    }

    /**
     * @return TableRenderer
     */
    private function createRenderer()
    {
        return new TableRenderer([
            'id'                => $this->options['id'],
            'columns'           => $this->columns,
            'limit'             => $this->limit,
            'attributeOptions'  => $this->attributeOptions,
            'data'              => $this->models,
            'columnClass'       => TabularColumn::className(),
            'context'           => $this
        ]);
    }
}