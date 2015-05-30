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
use yii\web\View;
use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\bootstrap\Button;
use unclead\widgets\assets\MultipleInputAsset;


/**
 * Widget for rendering multiple input for an attribute of model.
 *
 * @author Eugene Tupikov <unclead.nsk@gmail.com>
 */
class MultipleInput extends InputWidget
{
    const ACTION_ADD    = 'plus';
    const ACTION_REMOVE = 'remove';

    /**
     * @var ActiveRecord[]|array[] input data
     */
    public $data = null;

    /**
     * @var array columns configuration
     */
    public $columns = [];

    /**
     * @var integer inputs limit
     */
    public $limit;

    /**
     * @var string generated template, internal variable.
     */
    protected $template;

    /**
     * @var array js templates collected from js which has been registered during rendering of widgets
     */
    protected $jsTemplates = [];

    /**
     * @var string
     */
    protected $replacementKeys;

    /**
     * Initialization.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->initData();
        $this->initColumns();
        parent::init();
    }

    /**
     * Initializes data.
     */
    protected function initData()
    {
        if (is_null($this->data) && $this->model instanceof Model) {
            foreach ((array) $this->model->{$this->attribute} as $index => $value) {
                $this->data[$index] = $value;
            }
        }
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        foreach ($this->columns as $i => $column) {
            $column = Yii::createObject(array_merge([
                'class' => MultipleInputColumn::className(),
                'widget' => $this,
            ], $column));
            $this->columns[$i] = $column;
        }
    }

    /**
     * This function tries to guess the columns to show from the given data
     * if [[columns]] are not explicitly specified.
     */
    protected function guessColumns()
    {
        if (empty($this->columns) && $this->hasModel()) {
            $this->columns = [
                [
                    'name' => $this->attribute,
                    'type' => MultipleInputColumn::TYPE_TEXT_INPUT
                ]
            ];
        }
    }

    /**
     * Run widget.
     */
    public function run()
    {
        $content = [];

        if ($this->hasHeader()) {
            $content[] = $this->renderHeader();
        }

        $content[] = $this->renderBody();
        $content = Html::tag('table', implode("\n", $content), [
            'class' => 'multiple-input-list table table-condensed'
        ]);

        $this->registerClientScript();
        return Html::tag( 'div', $content, [
            'id' => $this->getId(),
            'class' => 'multiple-input'
        ]);
    }

    /**
     * Renders the header.
     *
     * @return string
     */
    private function renderHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column MultipleInputColumn */
            $cells[] = $column->renderHeaderCell();
        }
        if (is_null($this->limit) || $this->limit > 1) {
            $cells[] = Html::tag('th', '', [
                'class' => 'list-cell__button'
            ]);
        }

        return Html::tag('thead', Html::tag('tr', implode("\n", $cells)));
    }

    /**
     * Check that at least one column has a header.
     *
     * @return bool
     */
    private function hasHeader()
    {
        foreach ($this->columns as $column) {
            /* @var $column MultipleInputColumn */
            if ($column->hasHeader()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Renders the body.
     *
     * @return string
     */
    protected function renderBody()
    {
        $rows = [];
        if (!empty($this->data)) {
            foreach ($this->data as $index => $data) {
                $rows[] = $this->renderRow($index, $data);
            }
        } else {
            $rows[] = $this->renderRow(0);
        }
        return Html::tag('tbody', implode("\n", $rows));
    }

    private function getRowTemplate()
    {
        if (empty($this->template)) {
            $cells = [];
            $hiddenInputs = [];
            foreach ($this->columns as $columnIndex => $column) {
                /* @var $column MultipleInputColumn */
                $value = 'multiple-' . $column->name . '-value';
                $this->replacementKeys[$value] = $column->defaultValue;
                $value = '{' . $value . '}';

                if ($column->isHiddenInput()) {
                    $hiddenInputs[] = $column->renderCellContent($value);
                } else {
                    $cells[] = $column->renderCellContent($value);
                }
            }
            if (is_null($this->limit) || $this->limit > 1) {
                $cells[] = $this->renderActionColumn();
            }

            $this->template = implode("\n", $hiddenInputs);
            $this->template .= Html::tag('tr', implode("\n", $cells), [
                'class' => 'multiple-input-list__item',
            ]);

            if (is_array($this->getView()->js) && array_key_exists(View::POS_READY, $this->getView()->js)) {
                $this->collectJsTemplates();
            }
        }

        return $this->template;
    }

    private function collectJsTemplates()
    {
        $this->jsTemplates = [];
        foreach ($this->getView()->js[View::POS_READY] as $key => $js) {
            if (preg_match('/\(.#[^)]+{multiple-index}[^)]+\)/', $js) === 1) {
                $this->jsTemplates[] = $js;
                unset($this->getView()->js[View::POS_READY][$key]);
            }
        }
    }

    /**
     * Renders the action column.
     *
     * @return string
     * @throws \Exception
     */
    private function renderActionColumn()
    {
        $button = Button::widget(
            [
                'tagName' => 'div',
                'encodeLabel' => false,
                'label' => Html::tag('i', null, ['class' => 'glyphicon glyphicon-{multiple-btn-action}']),
                'options' => [
                    'id' => $this->getElementId('button'),
                    'class' => "{multiple-btn-type} multiple-input-list__btn btn js-input-{multiple-btn-action}",
                ]
            ]
        );
        return Html::tag('td', $button, [
            'class' => 'list-cell__button',
        ]);
    }

    /**
     * Renders the row.
     *
     * @param int $index
     * @param ActiveRecord|array $data
     * @return mixed
     * @throws InvalidConfigException
     */
    private function renderRow($index, $data = null)
    {
        $btnAction = $index == 0 ? self::ACTION_ADD : self::ACTION_REMOVE;
        $btnType = $index == 0 ? 'btn-default' : 'btn-danger';
        $search = ['{multiple-index}', '{multiple-btn-action}', '{multiple-btn-type}'];
        $replace = [$index, $btnAction, $btnType];

        foreach ($this->columns as $column) {
            /* @var $column MultipleInputColumn */
            $search[] = '{multiple-' . $column->name . '-value}';
            $replace[] = $column->prepareValue($data);
        }

        $row = str_replace($search, $replace, $this->getRowTemplate());

        foreach ($this->jsTemplates as $js) {
            $this->getView()->registerJs(strtr($js, ['{multiple-index}' => $index]), View::POS_READY);
        }
        return $row;
    }

    /**
     * Returns element's name.
     *
     * @param string $name
     * @param string $index
     * @return string
     */
    public function getElementName($name, $index = null)
    {
        if ($index === null) {
            $index = '{multiple-index}';
        }
        return $this->getInputNamePrefix($name) . (
            count($this->columns) > 1
                ? '[' . $index . '][' . $name . ']'
                : '[' . $name . '][' . $index . ']'
        );
    }

    /**
     * Return prefix for name of input.
     *
     * @param string $name input name
     * @return string
     */
    private function getInputNamePrefix($name)
    {
        if ($this->hasModel()) {
            if (empty($this->columns) || (count($this->columns) == 1 && $this->model->hasProperty($name))) {
                return $this->model->formName();
            }
            return Html::getInputName($this->model, $this->attribute);
        }
        return $this->name;
    }

    /**
     * Returns element id.
     *
     * @param $name
     * @return mixed
     */
    public function getElementId($name)
    {
        return $this->normalize($this->getElementName($name));
    }

    /**
     * Normalization name.
     *
     * @param $name
     * @return mixed
     */
    private  function normalize($name) {
        return str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], strtolower($name));
    }

    /**
     * Register script.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        MultipleInputAsset::register($view);
        $options = Json::encode(
            [
                'id'            => $this->getId(),
                'template'      => $this->getRowTemplate(),
                'jsTemplates'   => $this->jsTemplates,
                'btnAction'     => self::ACTION_REMOVE,
                'btnType'       => 'btn-danger',
                'limit'         => $this->limit,
                'replacement'   => $this->replacementKeys,
            ]
        );
        $id = $this->options['id'];
        $js = "jQuery('#$id').multipleInput($options);";
        $this->getView()->registerJs($js);
    }
}