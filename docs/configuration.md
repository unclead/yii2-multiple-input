#Configuration

Widget support the following options that are additionally recognized over and above the configuration options in the InputWidget.

##Base options

**max** *integer*: maximum number of rows. If not set will default to unlimited

**min** *integer*: minimum number of rows. Set to `0` if you need the empty list in case you don't have any data

**attributeOptions** *array*: client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when
you use widget without a model, since in this case widget is not able to detect client-side options automatically

**addButtonPosition** *integer|array*: the position(s) of `add` button.
This can be `MultipleInput::POS_HEADER`, `MultipleInput::POS_ROW` or `MultipleInput:PO_FOOTER`

**addButtonOptions** *array*: the HTML options for `add` button. Can contains `class` and `label` keys

**removeButtonOptions** *array*: the HTML options for `add` button. Can contains `class` and `label` keys

**data** *array*: array of values in case you use widget without model

**models** *array*: the list of models. Required in case you use `TabularInput` widget

**allowEmptyList** *boolean*: whether to allow the empty list

**columnClass** *string*: the name of column class. You can specify your own class to extend base functionality.
Defaults to `unclead\multipleinput\MultipleInputColumn` for `MultipleInput` and `unclead\multipleinput\TabularColumn` for `TabularInput`.

**rendererClass** *string*: the name of renderer class. You can specify your own class to extend base functionality.
Defaults to `unclead\multipleinput\renderers\TableRenderer`.

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

##Column options

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


##Input types

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