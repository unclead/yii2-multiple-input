#Yii2 Multiple input widget.
Yii2 widget for handle multiple inputs for an attribute of model

[![Latest Stable Version](https://poser.pugx.org/unclead/yii2-multiple-input/v/stable)](https://packagist.org/packages/unclead/yii2-multiple-input) [![Total Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/downloads)](https://packagist.org/packages/unclead/yii2-multiple-input) [![Latest Unstable Version](https://poser.pugx.org/unclead/yii2-multiple-input/v/unstable)](https://packagist.org/packages/unclead/yii2-multiple-input) [![License](https://poser.pugx.org/unclead/yii2-multiple-input/license)](https://packagist.org/packages/unclead/yii2-multiple-input)


##Installation


The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require  unclead/yii2-multiple-input "*"
```

or add

```
"unclead/yii2-multiple-input": "*"
```

to the require section of your `composer.json` file.

##Usage

### Input with one column

![Single column example](./docs/images/single-column.gif?raw=true)

For example you want to have an ability of entering several emails of user on profile page.
In this case you can use yii2-multiple-input widget like in the following code

```php
use unclead\widgets\MultipleInput;

<?= $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit' => 5
    ])
    ->label(false);
?>
```

You can find more detail about this use case [here](docs/single_column.md)

### Input with multiple column in each row

![Multiple columns example](./docs/images/multiple-column.gif?raw=true)

For example you keep some data in json format in attribute of model. Imagine that it is an absctract user schedule with keys: user_id, day, priority

On the edit page you want to be able to manage this schedule and you can you yii2-multiple-input widget like in the following code

```php

use unclead\widgets\MultipleInput;
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
            'type'  => 'dropDownList',
            'title' => 'Day',
            'value' => function($data) {
                return $data['day'];
            },
            'defaultValue' => 1,
            'items' => [
                '0' => 'Saturday',
                '1' => 'Monday'
            ],
            'options' => [

            ]
        ],
        [
            'name'  => 'priority',
            'title' => 'Priority',
            'options' => [
                'class' => 'input-priority'
            ]
        ]
    ]
 ]);
```

You can find more detail about this use case [here](docs/multiple_columns.md)

> Also you can find source code of examples [here](./examples/)

## Configuration

Widget support the following options that are additionally recognized over and above the configuration options in the InputWidget:

- `limit`: *integer*: rows limit
- `columns` *array*: the row columns configuration where you can set the following properties:
  - `name` *string*: input name. *Required options*
  - `type` *string*: type of the input. If not set will default to `textInput` **TBD**
  - `title` *string*: the column title
  - `value` *Closure*: you can set it to an anonymous function with the following signature: ```function($data) { return'something'; }```
  - `defaultValue` *string*: default value of column's input,
  - `items` *array*: the items for drop down list if you set column type like as dropDownList
  - `options` *array*: the HTML options of column's input


##License

**yii2-multiple-input** is released under the BSD 3-Clause License. See the bundled [LICENSE.md](./LICENSE.md) for details.
