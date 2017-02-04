##Renderers

Currently widget supports two type of renderers

###TableRenderer
![Table renderer](./images/table-renderer.jpg?raw=true)

This renderer is enabled by default.

###ListRenderer
![List renderer](./images/list-renderer.jpg?raw=true)

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