<?php

namespace unclead\widgets;

use Yii;

/**
 * Class TranslationTrait
 * @package unclead\widgets
 */
trait TranslationTrait
{
    /**
     * Подключение переводов для компонентов из yii2-commons
     */
    protected static function registerTranslations()
    {
        if (!isset(Yii::$app->i18n->translations['unclead-widgets'])) {
            Yii::$app->i18n->translations['unclead-widgets'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages/',
                'forceTranslation' => true,
                'fileMap' => [
                    'unclead-widgets' => 'common.php',
                ],
            ];
        }
    }
}