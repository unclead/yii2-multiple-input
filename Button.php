<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;

/**
 * Class Button
 * @package unclead\widgets
 */
class Button extends \yii\bootstrap\Button
{
    use TranslationTrait;

    const TYPE_SUCCESS  = 'btn-success';
    const TYPE_DEFAULT  = 'btn-default';
    const TYPE_PRIMARY  = 'btn-primary';
    const TYPE_INFO     = 'btn-info';
    const TYPE_DANGER   = 'btn-danger';
    const TYPE_WARNING  = 'btn-warning';

    const SIZE_LARGE    = 'btn-lg';
    const SIZE_SMALL    = 'btn-sm';
    const SIZE_EXTRA_SMALL = 'btn-xs';

    /**
     * @var null icon css class
     */
    public $iconClass = null;

    /**
     * @var string button type
     */
    public $type = self::TYPE_DEFAULT;

    /**
     * @var string button size
     */
    public $size;

    /**
     * @var string tag name
     */
    public $tagName = 'a';

    /**
     * @var string
     */
    public $url;

    public function init()
    {
        self::registerTranslations();
        parent::init();
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        if (!empty($this->iconClass)) {
            $icon = Html::tag('i', null, ['class' => $this->iconClass]);
            $this->label = $icon . ($this->label ? '&nbsp;&nbsp;' . $this->label : '');
            $this->encodeLabel = false;
        }

        if (!empty($this->type)) {
            Html::addCssClass($this->options, $this->type);
        }

        if (!empty($this->size)) {
            Html::addCssClass($this->options, $this->size);
        }

        if (!empty($this->url)) {
            $this->options['href'] = Url::to($this->url);
        }

        echo Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options);
        $this->registerPlugin('button');
    }

    /**
     * Render "Cancel" button.
     *
     * @return string
     */
    public static function cancel() {
        self::registerTranslations();
        return static::widget([
            'label' => Yii::t('unclead-widgets', 'BUTTON_CANCEL'),
            'type'  => self::TYPE_DEFAULT,
            'url'   => ['index']
        ]);
    }

    /**
     * Render "Create" или "Update" button.
     *
     * @param ActiveRecord $model
     * @return string
     */
    public static function submit($model = null) {
        self::registerTranslations();
        if (!is_null($model)) {
            return Html::submitButton(
                $model->isNewRecord ? Yii::t('unclead-widgets', 'BUTTON_CREATE') : Yii::t('unclead-widgets', 'BUTTON_UPDATE'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
            );
        } else {
            return Html::submitButton(Yii::t('unclead-widgets', 'BUTTON_SUBMIT'), ['class' => 'btn btn-primary']);
        }
    }

    /**
     * Render "Update" button.
     *
     * @return string
     */
    public static function update() {
        self::registerTranslations();
        return Html::submitButton(Yii::t('unclead-widgets', 'BUTTON_UPDATE'), ['class' => 'btn btn-primary']);
    }

    /**
     * Render "Delete button"
     *
     * @param ActiveRecord $model
     * @return string
     */
    public static function delete($model) {
        self::registerTranslations();
        return $model->isNewRecord ? '' : static::widget([
            'label' => Yii::t('unclead-widgets', 'BUTTON_DELETE'),
            'type'  => self::TYPE_DANGER,
            'url'   => ['delete', 'id' => $model->primaryKey],
            'options' => [
                'data-method' => 'post',
                'data-confirm' => Yii::t('unclead-widgets' , 'CONFIRM_DELETE'),
            ],
        ]);
    }
}