<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\widgets;

use unclead\widgets\components\BaseColumn;
use yii\base\Model;

/**
 * Class TabularColumn
 * @package unclead\widgets
 */
class TabularColumn extends BaseColumn
{
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
            $index = '{multiple-index}';
        }
        $elementName = '[' . $index . '][' . $this->name . ']';
        $prefix = $withPrefix ? $this->getModel()->formName() : '';
        return  $prefix . $elementName;
    }


    public function getFirstError($index)
    {
        return $this->getModel()->getFirstError($this->name);
    }

    protected function ensureModel($model)
    {
        return $model instanceof Model;
    }


}