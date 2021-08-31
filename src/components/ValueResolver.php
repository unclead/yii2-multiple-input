<?php

/**
 * @link https://github.com/unclead/yii2-multiple-input
 * @copyright Copyright (c) 2014 unclead
 * @license https://github.com/unclead/yii2-multiple-input/blob/master/LICENSE.md
 */

namespace unclead\multipleinput\components;


use yii\base\Model;
use yii\db\ActiveRecordInterface;
use yii\helpers\ArrayHelper;

/**
 * Class ValuePreparer.
 *
 * @package unclead\multipleinput\components
 */
class ValueResolver
{
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function resolve(string $name, $data, $defaultValue = null)
    {
        $value = null;
        if ($data instanceof ActiveRecordInterface) {
            $value = $this->resolveActiveRecordValue($name, $data);
        } elseif ($data instanceof Model) {
            $value = $data->{$name};
        } elseif (is_array($data)) {
            $value = $data[$name] ?? null;
        } elseif(is_string($data) || is_numeric($data)) {
            $value = $data;
        }

        if ($defaultValue !== null && $this->isEmpty($value)) {
            $value = $defaultValue;
        }

        return $value;
    }

    /**
     * @param string $name
     * @param ActiveRecordInterface $model
     * @return mixed
     *
     * @throws \RuntimeException
     */
    private function resolveActiveRecordValue(string $name, ActiveRecordInterface $model)
    {
        if ($model->canGetProperty($name)) {
            return $model->{$name};
        }

        $relation = $model->getRelation($name, false);
        if ($relation !== null) {
            return $relation->findFor($name, $model);
        }

        throw new \RuntimeException('Failed to resolve value for column: ' . $name);
    }

    private function isEmpty($value): bool
    {
        return $value === null || $value === [] || $value === '';
    }
}
