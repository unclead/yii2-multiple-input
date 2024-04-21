<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput\renderers;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use unclead\multipleinput\components\BaseColumn;

/**
 * Class TableRenderer
 * @package unclead\multipleinput\renderers
 */
class TableRenderer extends BaseRenderer
{
    /**
     * @return mixed
     */
    protected function internalRender()
    {
        $content = [];

        if ($this->hasHeader()) {
            $content[] = $this->renderHeader();
        }

        $content[] = $this->renderBody();
        $content[] = $this->renderFooter();

        $options = [];
        Html::addCssClass($options, 'multiple-input-list');

        if ($this->isBootstrapTheme()) {
            Html::addCssClass($options, 'table table-condensed table-renderer');
        }

        $content = Html::tag('table', implode("\n", $content), $options);

        return Html::tag('div', $content, [
            'id' => $this->id,
            'class' => 'multiple-input'
        ]);
    }

    /**
     * Renders the header.
     *
     * @return string
     */
    public function renderHeader()
    {
        $cells = [];
        if ($this->isAddButtonPositionRowBegin()) {
            $cells[] = $this->renderButtonHeaderCell();
        }

        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $cells[] = $this->renderHeaderCell($column);
        }

        if ($this->max === null || ($this->max >= 1 && $this->max !== $this->min)) {
            $button = $this->isAddButtonPositionHeader() ? $this->renderAddButton() : '';

            if ($this->cloneButton) {
                $cells[] = $this->renderButtonHeaderCell();
            }

            $cells[] = $this->renderButtonHeaderCell($button);
        }

        return Html::tag('thead', Html::tag('tr', implode("\n", $cells)));
    }

    /**
     * Renders the footer.
     *
     * @return string
     */
    public function renderFooter()
    {
        if (!$this->isAddButtonPositionFooter()) {
            return '';
        }

        $columnsCount = 0;
        foreach ($this->columns as $column) {
            if (!$column->isHiddenInput()) {
                $columnsCount++;
            }
        }

        if ($this->cloneButton) {
            $columnsCount++;
        }

        if ($this->isAddButtonPositionRowBegin()) {
            $columnsCount++;
        }

        $cells = [];
        $cells[] = Html::tag('td', '&nbsp;', ['colspan' => $columnsCount]);
        $cells[] = Html::tag('td', $this->renderAddButton(), [
            'class' => 'list-cell__button'
        ]);

        return Html::tag('tfoot', Html::tag('tr', implode("\n", $cells)));
    }


    /**
     * Check that at least one column has a header.
     *
     * @return bool
     */
    private function hasHeader()
    {
        if ($this->min === 0 || $this->isAddButtonPositionHeader()) {
            return true;
        }

        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            if ($column->title) {
                return true;
            }
        }

        return false;
    }

    /**
     * Renders the header cell.
     * @param BaseColumn $column
     * @return null|string
     */
    private function renderHeaderCell($column)
    {
        if ($column->isHiddenInput()) {
            return null;
        }

        $options = $column->headerOptions;
        Html::addCssClass($options, 'list-cell__' . $column->name);

        return Html::tag('th', $column->title, $options);
    }

    /**
     * Renders the button header cell.
     * @param string
     * @return string
     */
    private function renderButtonHeaderCell($button = '')
    {
        return Html::tag('th', $button, [
            'class' => 'list-cell__button'
        ]);
    }

    /**
     * Renders the body.
     *
     * @return string
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\InvalidParamException
     */
    protected function renderBody()
    {
        return Html::tag('tbody', implode("\n", $this->renderRows()));
    }

    /**
     * Renders the row content.
     *
     * @param int $index
     * @param ActiveRecordInterface|array $item
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function renderRowContent($index = null, $item = null, $rowIndex = null)
    {
        $cells = [];
        $hiddenInputs = [];

        if (!$this->isFixedNumberOfRows() && $this->isAddButtonPositionRowBegin()) {
            $cells[] = $this->renderActionColumn($index, $item, $rowIndex, true);
        }

        $columnIndex = 0;
        foreach ($this->columns as $column) {
            /* @var $column BaseColumn */
            $column->setModel($item);
            if ($column->isHiddenInput()) {
                $hiddenInputs[] = $this->renderCellContent($column, $index, $columnIndex++);
            } else {
                $cells[] = $this->renderCellContent($column, $index, $columnIndex++);
            }
        }

        if ($this->cloneButton) {
            $cells[] = $this->renderCloneColumn();
        }

        if (!$this->isFixedNumberOfRows()) {
            $cells[] = $this->renderActionColumn($index, $item, $rowIndex);
        }

        if ($hiddenInputs) {
            $hiddenInputs = implode("\n", $hiddenInputs);
            $cells[0] = preg_replace('/^(<td[^>]+>)(.*)(<\/td>)$/s', '${1}' . $hiddenInputs . '$2$3', $cells[0]);
        }

        $content = Html::tag('tr', implode("\n", $cells), $this->prepareRowOptions($index, $item));

        if ($index !== null) {
            $content = str_replace('{' . $this->getIndexPlaceholder() . '}', $index, $content);
        }

        return $content;
    }

    /**
     * Prepares the row options.
     *
     * @param int $index
     * @param ActiveRecordInterface|array $item
     * @return array
     */
    protected function prepareRowOptions($index, $item)
    {
        if (is_callable($this->rowOptions)) {
            $options = call_user_func($this->rowOptions, $item, $index, $this->context);
        } else {
            $options = $this->rowOptions;
        }

        $options['data-index'] = '{' . $this->getIndexPlaceholder() . '}';

        Html::addCssClass($options, 'multiple-input-list__item');

        return $options;
    }

    /**
     * Renders the cell content.
     *
     * @param BaseColumn $column
     * @param int|null $index
     * @param int|null $columnIndex
     * @return string
     *
     * @todo rethink visibility level (make it private)
     */
    public function renderCellContent($column, $index, $columnIndex = null)
    {
        $id    = $column->getElementId($index);
        $name  = $column->getElementName($index);

        /**
         * This class inherits iconMap from BaseRenderer
         * If the input to be rendered is a drag column, we give it the appropriate icon class
         * via the $options array
         */
        $options = ['id' => $id];
        if ($column->type === BaseColumn::TYPE_DRAGCOLUMN) {
            $options = ArrayHelper::merge($options, ['class' => $this->iconMap['drag-handle']]);
        }

        $input = $column->renderInput($name, $options, [
            'id' => $id,
            'name' => $name,
            'indexPlaceholder' => $this->getIndexPlaceholder(),
            'index' => $index,
            'columnIndex' => $columnIndex,
            'context' => $this->context,
        ]);

        if ($column->isHiddenInput()) {
            return $input;
        }

        $hasError = false;
        $error = '';

        if ($index !== null) {
            $error = $column->getFirstError($index);
            $hasError = !empty($error);
        }

        if ($column->enableError) {
            $input .= "\n" . $column->renderError($error);
        }

        $wrapperOptions = ['class' => 'field-' . $id];
        if ($this->isBootstrapTheme()) {
            Html::addCssClass($wrapperOptions, 'form-group');
        }

        if ($hasError) {
            Html::addCssClass($wrapperOptions, 'has-error');
        }

        if (is_callable($column->columnOptions)) {
            $columnOptions = call_user_func($column->columnOptions, $column->getModel(), $index, $this->context);
        } else {
            $columnOptions = $column->columnOptions;
        }

        Html::addCssClass($columnOptions, 'list-cell__' . $column->name);

        $input = Html::tag('div', $input, $wrapperOptions);

        return Html::tag('td', $input, $columnOptions);
    }


    /**
     * Renders the action column.
     *
     * @param null|int|string $index
     * @param null|ActiveRecordInterface|array $item
     * @param int $rowIndex
     * @return string
     */
    private function renderActionColumn(
        $index = null,
        $item = null,
        $rowIndex = null,
        $isFirstColumn = false
    )
    {
        $content = $this->getActionButton($index, $rowIndex, $isFirstColumn) . $this->getExtraButtons($index, $item);

        return Html::tag('td', $content, [
            'class' => 'list-cell__button',
        ]);
    }

    /**
     * Renders the clone column.
     *
     * @return string
     */
    private function renderCloneColumn()
    {
        return Html::tag('td', $this->renderCloneButton(), [
            'class' => 'list-cell__button',
        ]);
    }

    /**
     * @param int|string|null $index
     * @param int $rowIndex
     * @return string
     */
    private function getActionButton($index = null, $rowIndex = null, $isFirstColumn = false)
    {
        if ($index === null || $this->min === 0) {
            if ($isFirstColumn) {
                return $this->isAddButtonPositionRowBegin() ? $this->renderRemoveButton() : '';
            }

            return $this->isAddButtonPositionRowBegin() ? '' : $this->renderRemoveButton();
        }

        // rowIndex is zero-based, so we have to increment it to properly cpmpare it with min number of rows
        $rowIndex++;

        if ($rowIndex < $this->min) {
            return '';
        }

        if ($rowIndex === $this->min) {
            if ($isFirstColumn) {
                return $this->isAddButtonPositionRowBegin() ? $this->renderAddButton() : '';
            }


            return $this->isAddButtonPositionRow() ? $this->renderAddButton() : '';
        }

        if ($isFirstColumn) {
            return $this->isAddButtonPositionRowBegin() ? $this->renderRemoveButton() : '';
        }

        return $this->isAddButtonPositionRowBegin() ? '' : $this->renderRemoveButton();
    }

    private function renderAddButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-plus',
        ];

        Html::addCssClass($options, $this->addButtonOptions['class']);

        return Html::tag('div', $this->addButtonOptions['label'], $options);
    }

    /**
     * Renders remove button.
     *
     * @return string
     */
    private function renderRemoveButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-remove',
        ];

        Html::addCssClass($options, $this->removeButtonOptions['class']);

        return Html::tag('div', $this->removeButtonOptions['label'], $options);
    }

    /**
     * Renders clone button.
     *
     * @return string
     */
    private function renderCloneButton()
    {
        $options = [
            'class' => 'multiple-input-list__btn js-input-clone',
        ];

        Html::addCssClass($options, $this->cloneButtonOptions['class']);

        return Html::tag('div', $this->cloneButtonOptions['label'], $options);
    }

    /**
     * Returns template for using in js.
     *
     * @return string
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function prepareTemplate()
    {
        return $this->renderRowContent();
    }
}
