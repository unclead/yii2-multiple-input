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
use yii\widgets\InputWidget;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Button;
use unclead\widgets\assets\MultipleInputAsset;


/**
 * Widget for rendering multiple input for an attribute of model.
 *
 * @author Eugene Tupikov <unclead.nsk@gmail.com>
 */
class MultipleInput extends InputWidget
{
    const ACTION_ADD = 'plus';
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
        parent::init();

        if (is_null($this->data) && $this->model instanceof Model) {
            foreach ((array) $this->model->{$this->attribute} as $index => $value) {
                $this->data[$index] = $value;
            }
        }
    }


    /**
     * Run widget.
     */
    public function run()
    {
        echo Html::beginTag('div', [
            'id' => $this->getId(),
            'class' => 'multiple-input'
        ]);
        echo Html::beginTag('table', [
            'class' => 'multiple-input-list table table-condensed'
        ]);

        if ($this->hasHeader()) {
            $this->renderHeader();
        }

        echo Html::beginTag('tbody');
        if (!empty($this->data)) {
            foreach ($this->data as $index => $data) {
                $this->renderRow($index, $data);
            }
        } else {
            $this->renderRow(0);
        }
        echo Html::endTag('tbody');

        echo Html::endTag('table');
        echo Html::endTag('div');

        $this->registerClientScript();
    }
    /**
     * Render header.
     *
     * @return void
     */
    private function renderHeader()
    {
        echo Html::beginTag('thead');
        echo Html::beginTag('tr');
        foreach ($this->getColumns() as $column) {
            $type = ArrayHelper::getValue($column, 'type', 'textInput');
            if ($type == 'hiddenInput') {
                continue;
            }
            $field = $column['name'];
            echo Html::beginTag('th', [
                'class' => 'list-cell__' . $field,
            ]);
            echo ArrayHelper::getValue($column, 'title', $field);
            echo Html::endTag('th');
        }
        if (is_null($this->limit) || $this->limit > 1) {
            echo Html::beginTag('th', [
                'class' => 'list-cell__button'
            ]);
            echo '&nbsp;';
            echo Html::endTag('th');
        }
        echo Html::endTag('tr');
        echo Html::endTag('thead');
    }

    /**
     * Check that at least one column has a header.
     *
     * @return boolean
     */
    private function hasHeader()
    {
        foreach ($this->getColumns() as $column) {
            if (array_key_exists('title', $column)) {
                return true;
            }
        }
        return false;
    }

    private function getRowTemplate()
    {
        if (empty($this->template)) {
            $this->template .= Html::beginTag('tr', [
                'class' => 'multiple-input-list__item',
            ]);
            $hiddenFields = '';
            foreach ($this->getColumns() as $columnIndex => $column) {
                $field = $column['name'];
                $name = $this->getElementName($field);
                $value = $field . '_value';
                $this->replacementKeys[$value] = ArrayHelper::getValue($column, 'defaultValue', '');
                $value = '{' . $value . '}';
                $options = ArrayHelper::getValue($column, 'options', []);
                $options['id'] = $this->getElementId($field);
                $type = ArrayHelper::getValue($column, 'type', 'textInput');
                if ($type == 'hiddenInput') {
                    $hiddenFields .= Html::hiddenInput($name, $value, $options);
                } else {
                    $this->template .= Html::beginTag('td', [
                        'class' => 'list-cell__' . $field,
                    ]);
                    $this->template .= Html::beginTag('div', [
                        'class' => 'form-group field-' . $options['id'],
                    ]);
                    $this->template .= Html::beginTag('div');
                    Html::addCssClass($options, 'form-control');
                    switch ($type) {
                        case 'dropDownList':
                        case 'listBox':
                        case 'checkboxList':
                        case 'radioList':
                            $options['selectedOption'] = $value;
                            $this->template .= Html::$type($name, null, $column['items'], $options);
                            break;
                        default:
                            if (method_exists('yii\helpers\Html', $type)) {
                                $this->template .= Html::$type($name, $value, $options);
                            // TODO https://github.com/unclead/yii2-multiple-input/issues/1
                            /*
                            } elseif (class_exists($type) && method_exists($type, 'widget')) {
                                $this->template .= $type::widget(array_merge($options, [
                                    'name'  => $name,
                                    'value' => $value,
                                ]));
                            */
                            } else {
                                throw new InvalidConfigException("Invalid column type '$type'");
                            }
                    }
                    $this->template .= Html::endTag('div');
                    $this->template .= Html::endTag('div');
                    $this->template .= Html::endTag('td');
                }
            }
            if (is_null($this->limit) || $this->limit > 1) {
                $this->template .= Html::beginTag('td', [
                    'class' => 'list-cell__button',
                ]);
                $this->template .= $hiddenFields;
                $this->template .= Button::widget(
                    [
                        'tagName' => 'div',
                        'encodeLabel' => false,
                        'label' => Html::tag('i', null, ['class' => 'glyphicon glyphicon-{btn_action}']),
                        'options' => [
                            'id' => $this->getElementId('button'),
                            'class' => "{btn_type} multiple-input-list__btn btn js-input-{btn_action}",
                        ]
                    ]
                );
                $this->template .= Html::endTag('td');
            }
            $this->template .= Html::endTag('tr');
        }
        return $this->template;
    }

    /**
     * Render row.
     *
     * @param integer $index
     * @param ActiveRecord|array $data
     * @throws InvalidConfigException
     */
    private function renderRow($index, $data = null)
    {
        $btnAction = $index == 0 ? self::ACTION_ADD : self::ACTION_REMOVE;
        $btnType   = $index == 0 ? 'btn-default' : 'btn-danger';
        $search = ['{index}', '{btn_action}', '{btn_type}'];
        $replace = [$index, $btnAction, $btnType];

        foreach ($this->getColumns() as $column) {
            if (!array_key_exists('name', $column)) {
                throw new InvalidConfigException("The 'name' option is required.");
            }
            $search[] = '{' . $column['name'] . '_value}';
            $replace[] = $this->prepareColumnValue($column, $data);
        }

        echo str_replace($search, $replace, $this->getRowTemplate());
    }


    private function getColumns()
    {
        if (empty($this->columns) && $this->hasModel()) {
            return [
                ['name' => $this->attribute]
            ];
        }
        return $this->columns;
    }
    
    /**
     * Preparing column's value.
     *
     * @param $column
     * @param $data
     * @return mixed
     */
    private function prepareColumnValue($column, $data)
    {
        if (isset($column['value'])) {
            $value = $column['value'];
            if ($value instanceof \Closure) {
                $value = call_user_func($value, $data);
            }
        } else {
            if ($data instanceof ActiveRecord) {
                $value = $data->{$column['name']};
            } elseif (is_array($data)) {
                $value = $data[$column['name']];
            } elseif(is_string($data)) {
                $value = $data;
            }else {
                $value = ArrayHelper::getValue($column, 'defaultValue', '');
            }
        }
        return $value;
    }
    
    /**
     * Returns element's name.
     *
     * @param string $name
     * @param string $index
     * @return string
     */
    private function getElementName($name, $index = null)
    {
        if ($index === null) {
            $index = '{index}';
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
    private function getElementId($name)
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
                'btn_action'    => self::ACTION_REMOVE,
                'btn_type'      => 'btn-danger',
                'limit'         => $this->limit,
                'replacement'   => $this->replacementKeys,
            ]
        );
        $id = $this->options['id'];
        $js = "jQuery('#$id').multipleInput($options);";
        $this->getView()->registerJs($js);
    }
}