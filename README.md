#Yii2 Multiple input widget.
Yii2 widget for handle multiple inputs for an attribute of model

[![License](https://poser.pugx.org/unclead/yii2-multiple-input/license.svg)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Monthly Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/d/monthly.png)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Daily Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/d/daily.png)](https://packagist.org/packages/unclead/yii2-multiple-input)


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

In case when input has one column

```
use unclead\widgets\MultipleInput;

<?= $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit' => 5
    ])
    ->label(false);
?>

```

In case when input has several columns

```

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

The configuration of widgets is described below

## Configuration

Widget support the following options that are additionally recognized over and above the configuration options in the InputWidget:

- `limit`: *integer*: rows limit
- `columns` *array*: the row columns configuration where you can set the following properties:
  - `name` *string*: input name. Required options
  - `type` *string*: type of the input. If not set will default to `textInput`
  - `title` *string*: the column title
  - `value` *Closure: you can set it to an anonymous function with the following signature:
  
```php
function($data) {
    return 'something';
}
```

  - `defaultValue` *string*: default value of column's input,
  - `items` *array*: the items for drop down list if you set column type like as dropDownList
  - `options` *array*: the HTML options of column's input

##Examples

Widget supports several use cases:

- [Single column example](docs/single_column.md)
- [Several columns example](docs/several_columns.md)

You cad find source code of examples [here](./examples/)
##License

**yii2-multiple-input** is released under the BSD 3-Clause License. See the bundled LICENSE.md for details.
