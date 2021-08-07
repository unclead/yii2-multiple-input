# Renderers

Currently widget supports three type of renderers

## TableRenderer

![Table renderer](https://raw.githubusercontent.com/unclead/yii2-multiple-input/master/resources/images/table-renderer.jpg?raw=true)

This renderer is enabled by default.

## ListRenderer

![List renderer](https://raw.githubusercontent.com/unclead/yii2-multiple-input/master/resources/images/list-renderer.jpg?raw=true)

To enable this renderer you have to use an option `rendererClass`

```php
<?php
echo $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::className(),
    'max' => 4,
    'allowEmptyList' => true,
    'rowOptions' => function($model) {
        $options = [];

        if ($model['priority'] > 1) {
            $options['class'] = 'danger';
        }
        return $options;
    },
```

## DivRenderer

![List renderer](https://raw.githubusercontent.com/unclead/yii2-multiple-input/master/resources/images/list-renderer.jpg?raw=true)

To enable this renderer you have to use an option `rendererClass`

```php
<?php
echo $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    'rendererClass' => \unclead\multipleinput\renderers\ListRenderer::class,
    'addButtonPosition' => MultipleInput::POS_ROW, // show add button inside of the row
    'extraButtons' => function ($model, $index, $context) {
        if ($index === 0) {
            return Html::tag('div', Yii::t('object', 'Add object'), ['class' => 'mi-after-add']);
        }

        return Html::tag('div', Yii::t('object', 'Remove object'), ['class' => 'mi-after-remove']);
    },
    'layoutConfig' => [
        'offsetClass' => 'col-md-offset-2',
        'labelClass' => 'col-md-2',
        'wrapperClass' => 'col-md-6',
        'errorClass' => 'col-md-offset-2 col-md-6',
        'buttonActionClass' => 'col-md-offset-1 col-md-2',
    ],
...
```

