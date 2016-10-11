##Usage

> You can find source code of examples [here](./examples/)

- [One column](#one-column)
- [Multiple columns](#multiple-columns)
- [Tabular input](#tabular-input)

##One column

![Single column example](./images/single-column.gif?raw=true)

For example you want to have an ability of entering several emails of user on profile page.
In this case you can use yii2-multiple-input widget like in the following code

```php
use unclead\multipleinput\MultipleInput;

...

<?php
    echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'max'               => 6,
        'allowEmptyList'    => false,
        'enableGuessTitle'  => true,
        'min'               => 2, // should be at least 2 rows
        'addButtonPosition' => MultipleInput::POS_HEADER // show add button in the header
    ])
    ->label(false);
?>
```

You can find more detail about this use case [here](multiple_input_single.md)

##Multiple columns

![Multiple columns example](./images/multiple-column.gif?raw=true)

For example you keep some data in json format in attribute of model. Imagine that it is an abstract user schedule with keys: user_id, day, priority

On the edit page you want to be able to manage this schedule and you can you yii2-multiple-input widget like in the following code

```php
use unclead\multipleinput\MultipleInput;

...

<?= $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    'max' => 4,
    'columns' => [
        [
            'name'  => 'user_id',
            'type'  => 'dropDownList',
            'title' => 'User',
            'defaultValue' => 1,
            'items' => [
                1 => 'User 1',
                2 => 'User 2'
            ]
        ],
        [
            'name'  => 'day',
            'type'  => \kartik\date\DatePicker::className(),
            'title' => 'Day',
            'value' => function($data) {
                return $data['day'];
            },
            'items' => [
                '0' => 'Saturday',
                '1' => 'Monday'
            ],
            'options' => [
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true
                ]
            ],
            'headerOptions' => [
                'style' => 'width: 250px;',
                'class' => 'day-css-class'
            ]
        ],
        [
            'name'  => 'priority',
            'enableError' => true,
            'title' => 'Priority',
            'options' => [
                'class' => 'input-priority'
            ]
        ],
        [
            'name'  => 'comment',
            'type'  => 'static',
            'value' => function($data) {
                return Html::tag('span', 'static content', ['class' => 'label label-info']);
            },
            'headerOptions' => [
                'style' => 'width: 70px;',
            ]
        ]
    ]
 ]);
?>
```

You can find more detail about this use case [here](multiple_input_multiple.md)

##Tabular input

For example you want to manage some models via tabular input. In this case you can use `TabularInput` widget which is based on `MultipleInput` widget.
Use the following code for this purpose:

```php
<?= TabularInput::widget([
    'models' => $models,
    'attributeOptions' => [
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
        'validateOnChange'          => false,
        'validateOnSubmit'          => true,
        'validateOnBlur'            => false,
    ],
    'columns' => [
        [
            'name'  => 'title',
            'title' => 'Title',
            'type'  => TabularInputColumn::TYPE_TEXT_INPUT,
        ],
        [
            'name'  => 'description',
            'title' => 'Description',
        ],
        [
            'name'  => 'file',
            'title' => 'File',
            'type'  => \vova07\fileapi\Widget::className(),
            'options' => [
                'settings' => [
                    'url' => ['site/fileapi-upload']
                ]
            ]
        ],
        [
            'name'  => 'date',
            'type'  => \kartik\date\DatePicker::className(),
            'title' => 'Day',
            'options' => [
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true
                ]
            ],
            'headerOptions' => [
                'style' => 'width: 250px;',
                'class' => 'day-css-class'
            ]
        ],
    ],
]) ?>
```

You can find more detail about this use case [here](tabular_input.md)