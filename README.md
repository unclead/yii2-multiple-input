#Yii2 Multiple input widget.
Yii2 widget for handle multiple inputs for an attribute of model

[![Latest Stable Version](https://poser.pugx.org/unclead/yii2-multiple-input/v/stable)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Total Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/downloads)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Daily Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/d/daily)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Latest Unstable Version](https://poser.pugx.org/unclead/yii2-multiple-input/v/unstable)](https://packagist.org/packages/unclead/yii2-multiple-input) 
[![License](https://poser.pugx.org/unclead/yii2-multiple-input/license)](https://packagist.org/packages/unclead/yii2-multiple-input)

##Latest release
The latest version of the extension is v1.2.16. Follow the [instruction](./UPGRADE.md) for upgrading from previous versions

Contents:

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Tips and tricks](#tips-and-tricks)
 - [How to customize buttons](#how-to-customize-buttons)
 - [Work with empty list](#work-with-empty-list)
 - [Guess column title](#guess-column-title)
 - [Ajax loading of a widget](#ajax-loading)
 - [Use of a widget's placeholder](#using-placeholder)
- [Javascript Events](#javascript-events)
- [Renderers](#renderers)

##Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require  unclead/yii2-multiple-input "~1.0"
```

or add

```
"unclead/yii2-multiple-input": "~1.0"
```

to the require section of your `composer.json` file.

## Configuration

Widget support the following options that are additionally recognized over and above the configuration options in the InputWidget.

### Base options

**limit** *integer*: rows limit. If not set will defaul to unlimited

**min** *integer*: minimum number of rows. Set to `0` if you need the empty list in case you don't have any data

**attributeOptions** *array*: client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when
  you use widget without a model, since in this case widget is not able to detect client-side options automatically

**addButtonPosition** *integer*: the position of `add` button. This can be MultipleInput::POS_HEADER or MultipleInput::POS_ROW.

**addButtonOptions** *array*: the HTML options for `add` button. Can contains `class` and `label` keys

**removeButtonOptions** *array*: the HTML options for `add` button. Can contains `class` and `label` keys

**data** *array*: array of values in case you use widget without model

**models** *array*: the list of models. Required in case you use `TabularInput` widget

**allowEmptyList** *boolean*: whether to allow the empty list

**columnClass** *string*: the name of column class. You can specify your own class to extend base functionality.
Defaults to `unclead\widgets\MultipleInputColumn` for `MultipleInput` and `unclead\widgets\TabularColumn` for `TabularInput`.

**columns** *array*: the row columns configuration where you can set the properties which is described below

**rowOptions** *array|\Closure*: the HTML attributes for the table body rows. This can be either an array
specifying the common HTML attributes for all body rows, or an anonymous function that returns an array of the HTML attributes.
It should have the following signature:

```php
function ($model, $index, $context)
```

- `$model`: the current data model being rendered
- `$index`: the zero-based index of the data model in the model array
- `$context`: the widget object

### Column options

**name** *string*: input name. *Required options*

**type** *string*: type of the input. If not set will default to `textInput`. Read more about the types described below

**title** *string*: the column title

**value** *Closure*: you can set it to an anonymous function with the following signature:

```php
function($data) {}
```

**defaultValue** *string*: default value of input

**items** *array*|*Closure*: the items for input with type dropDownList, listBox, checkboxList, radioList
or anonymous function which return array of items and has the following signature:

```php
function($data) {}
```

**options** *array*|*Closure*: the HTML attributes for the input, you can set it as array
or an anonymous function with the following signature:

```php
function($data) {}
```

**headerOptions** *array*: the HTML attributes for the header cell

**enableError** *boolean*: whether to render inline error for the input. Default to `false`

**errorOptions** *array*: the HTMl attributes for the error tag


### Input types

Each column in a row can has their own type. Widget supports:

- all yii2 html input types:
  - `textInput`
  - `dropDownList`
  - `radioList`
  - `textarea`
  - For more detail look at [Html helper class](http://www.yiiframework.com/doc-2.0/yii-helpers-html.html)
- input widget (widget that extends from `InputWidget` class). For example, `yii\widgets\MaskedInput`
- `static` to output a static HTML content

For using widget as column input you may use the following code:

```php
[
    'name'  => 'phone',
    'title' => 'Phone number',
    'type' => \yii\widgets\MaskedInput::className(),
    'options' => [
        'class' => 'input-phone',
        'mask' => '999-999-99-99'
    ]
]
```

##Usage

### Input with one column

![Single column example](./docs/images/single-column.gif?raw=true)

For example you want to have an ability of entering several emails of user on profile page.
In this case you can use yii2-multiple-input widget like in the following code

```php
use unclead\widgets\MultipleInput;

...

<?php
    echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit'             => 6,
        'allowEmptyList'    => false,
        'enableGuessTitle'  => true,
        'min'               => 2, // should be at least 2 rows
        'addButtonPosition' => MultipleInput::POS_HEADER // show add button in the header
    ])
    ->label(false);
?>
```

You can find more detail about this use case [here](docs/multiple_input_single.md)

### Input with multiple column in each row

![Multiple columns example](./docs/images/multiple-column.gif?raw=true)

For example you keep some data in json format in attribute of model. Imagine that it is an abstract user schedule with keys: user_id, day, priority

On the edit page you want to be able to manage this schedule and you can you yii2-multiple-input widget like in the following code

```php
use unclead\widgets\MultipleInput;

...

<?= $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    'limit' => 4,
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

You can find more detail about this use case [here](docs/multiple_input_multiple.md)

### Tabular input

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

You can find more detail about this use case [here](docs/tabular_input.md)

> Also you can find source code of examples [here](./docs/examples/)



## Tips and tricks

### How to customize buttons

You can customize `add` and `remove` buttons via `addButtonOptions` and `removeButtonOptions`. Here is the simple example
how you can use those options:

```php

    echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit' => 5,
        'addButtonOptions' => [
            'class' => 'btn btn-success',
            'label' => 'add' // also you can use html code
        ],
        'removeButtonOptions' => [
            'label' => 'remove'
        ]
    ])
    ->label(false);

```

### Work with empty list

In some cases you need to have the ability to delete all rows in the list. For this purpose you can use option `allowEmptyList` like in the example below:

```php

    echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit' => 5,
        'allowEmptyList' => true
    ])
    ->label(false);

```

Also you can set `0` in `min` option if you don't need first blank row when data is empty.

### Guess column title

Sometimes you can use the widget without defining columns but you want to have the column header of the table.
In this case you can use `enableGuessTitle` option like in the example below:

```php

    echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit' => 5,
        'allowEmptyList' => true,
        'enableGuessTitle' => true
    ])
    ->label(false);

```

### Ajax loading of a widget <a id="ajax-loading"></a>

Assume you want to load a widget via ajax and then show it inside modal window. In this case you MUST:

- Ensure that you specified ID of widget otherwise the widget will get random ID and it can be the same as id of others elements on the page.
- Ensure that you use the widget inside ActiveForm because it works incorrectly in this case.

You can fina an example of usage in a discussion of [issue](https://github.com/unclead/yii2-multiple-input/issues/58)

### Use of a widget's placeholder <a id="using-placeholder"></a>

You can use a placeholder `{multiple index}` in a widget configuration, e.g. for implementation of dependent drop down lists.

```php
    <?= $form->field($model, 'field')->widget(MultipleInput::className(), [
            'allowEmptyList' => false,
            'rowOptions' => [
                'id' => 'row{multiple_index}',
            ],
            'columns' => [
                [
                    'name'  => 'category',
                    'type'  => 'dropDownList',
                    'title' => 'Category',
                    'defaultValue' => '1',
                    'items' => [
                        '1' => 'Test 1',
                        '2' => 'Test 2',
                        '3' => 'Test 3',
                        '4' => 'Test 4',
                    ],
                    'options' => [
                        'onchange' => <<< JS
$.post("list?id=" + $(this).val(), function(data){
    console.log(data);
    $("select#subcat-{multiple_index}").html(data);
});
JS
                    ],
                ],
                [
                    'name'  => 'subcategory',
                    'type'  => 'dropDownList',
                    'title' => 'Subcategory',
                    'items' => [],
                    'options'=> [
                        'id' => 'subcat-{multiple_index}'
                    ],
                ],
            ]
    ]);
    ?>
```


## JavaScript events
This widget has following events:
 - `afterInit`: triggered after initialization
 - `afterAddRow`: triggered after new row insertion
 - `beforeDeleteRow`: triggered before the row removal
 - `afterDeleteRow`: triggered after the row removal

Example:
```js
jQuery('#multiple-input').on('afterInit', function(){
    console.log('calls on after initialization event');
}).on('beforeAddRow', function(e) {
    console.log('calls on before add row event');
}).on('afterAddRow', function(e) {
    console.log('calls on after add row event');
}).on('beforeDeleteRow', function(e, row){
    // row - HTML container of the current row for removal. 
    // For TableRenderer it is tr.multiple-input-list__item
    console.log('calls on before remove row event.');
    return confirm('Are you sure you want to delete row?')
}).on('afterDeleteRow', function(){
    console.log('calls on after remove row event');
});
```

## JavaScript operations
Dynamically operations in widget:
 - `add`: adding new row, **param** *object*: object with values for inputs, can be filled with <option> tags for dynamically added options for select (for ajax select).
 - `remove`: remove row, **param** *integer*: row number for removing, if not specified then removes last row.
 - `clear`: remove all rows

Examples:
```js
$('#multiple-input').multipleInput('add', {first: 10, second: '<option value="2" selected="selected">second</option>'});
$('#multiple-input').multipleInput('remove', 2);
$('#multiple-input').multipleInput('clear');
```

##Renderers

> Section is under development

Currently widget supports only `TableRenderer` which renders content in table format.

##License

**yii2-multiple-input** is released under the BSD 3-Clause License. See the bundled [LICENSE.md](./LICENSE.md) for details.
