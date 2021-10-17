# Configuration

Widget support the following options that are additionally recognized over and above the configuration options in the InputWidget.

## Base options

**theme** _string_: specify the theme of the widget. Available 2 themes:

* `default` with only widget css classes
* `bs` \(twitter bootstrap\) theme with additional BS ccs classes\). 

Default value is `bs`

**max** _integer_: maximum number of rows. If not set will default to unlimited

**min** _integer_: minimum number of rows. Set to `0` if you need the empty list in case you don't have any data

**prepend** _boolean_: add a new row to the beginning of the list, not to the end

**attributeOptions** _array_: client-side attribute options, e.g. enableAjaxValidation. You may use this property in case when you use widget without a model, since in this case widget is not able to detect client-side options automatically

**addButtonPosition** _integer\|array_: the position\(s\) of `add` button. This can be `MultipleInput::POS_HEADER`, `MultipleInput::POS_ROW`, `MultipleInput::POS_ROW_BEGIN` or `MultipleInput::POS_FOOTER`.

**addButtonOptions** _array_: the HTML options for `add` button. Can contains `class` and `label` keys

**removeButtonOptions** _array_: the HTML options for `remove` button. Can contains `class` and `label` keys

**cloneButton** _bool_: whether need to enable clone buttons or not

**cloneButtonOptions** _array_: the HTML options for `remove` button. Can contains `class` and `label` keys

**data** _array_: array of values in case you use widget without model

**models** _array_: the list of models. Required in case you use `TabularInput` widget

**allowEmptyList** _boolean_: whether to allow the empty list. **Deprecateed** use the `min` option instead

**columnClass** _string_: the name of column class. You can specify your own class to extend base functionality. Defaults to `unclead\multipleinput\MultipleInputColumn` for `MultipleInput` and `unclead\multipleinput\TabularColumn` for `TabularInput`.

**rendererClass** _string_: the name of renderer class. You can specify your own class to extend base functionality. Defaults to `unclead\multipleinput\renderers\TableRenderer`.

**columns** _array_: the row columns configuration where you can set the properties which is described below

**rowOptions** _array\|\Closure_: the HTML attributes for the table body rows. This can be either an array specifying the common HTML attributes for all body rows, or an anonymous function that returns an array of the HTML attributes. It should have the following signature:

```php
function ($model, $index, $context)
```

* `$model`: the current data model being rendered
* `$index`: the zero-based index of the data model in the model array
* `$context`: the widget object

**sortable** _bool_: whether need to enable sorting or not

**modelClass** _string_: a class of model which is used to render `TabularInput`. You must specify this property when a list of `models` is empty. If this property is not specified the widget will detect it based on a class of `models`

**extraButtons** _string\|\Closure_: the HTML content that will be rendered after the buttons. It can be either string or an anonymous function that returns a string which will be treated as HTML content. It should have the following signature:

```php
function ($model, $index, $context)
```

* `$model`: the current data model being rendered
* `$index`: the zero-based index of the data model in the model array
* `$context`: the MultipleInput widget object

**layoutConfig** _array_: CSS grid classes for horizontal layout \(only supported for `ListRenderer` class\). This must be an array with these keys:

* `'offsetClass'`: the offset grid class to append to the wrapper if no label is rendered
* `'labelClass'`: the label grid class
* `'wrapperClass'`: the wrapper grid class
* `'errorClass'`: the error grid class

**showGeneralError** _bool_: whether need to show error message for main attribute, when you don't want to validate particular input and want to validate a filed in general.

## Column options

**name** _string_: input name. _Required options_

**type** _string_: type of the input. If not set will default to `textInput`. Read more about the types described below

**title** _string_: the column title

**value** _Closure_: you can set it to an anonymous function with the following signature:

```php
function($data) {}
```

**defaultValue** _string_: default value of input

**items** _array_\|_Closure_: the items for input with type dropDownList, listBox, checkboxList, radioList or anonymous function which return array of items and has the following signature:

```php
function($data) {}
```

**options** _array_\|_Closure_: the HTML attributes for the input, you can set it as array or an anonymous function with the following signature:

```php
function($data) {}
```

**headerOptions** _array_: the HTML attributes for the header cell

**enableError** _boolean_: whether to render inline error for the input. Default to `false`

**errorOptions** _array_: the HTMl attributes for the error tag

**nameSuffix** _string_: the unique prefix for attribute's name to avoid id duplication e.g. in case of using several copies of the widget on a page and one column is a Select2 widget

**tabindex** _integer_: use it to customize a form element `tabindex`

**attributeOptions** _array_: client-side options of the attribute, e.g. enableAjaxValidation. You can use this property for custom configuration of the column (attribute). By default, the column will use options which are defined on widget level.

_Supported versions >= 2.1.0

**columnOptions** _array|\Closure_: the HTML attributes for the indivdual table body column. This can be either an array specifying the common HTML attributes for indivdual body column, or an anonymous function that returns an array of the HTML attributes. 

It should have the following signature:
```php
function ($model, $index, $context)
```
* `$model`: the current data model being rendered
* `$index`: the zero-based index of the data model in the model array
* `$context`: the widget object

_Supported versions >= 2.18.0_

**inputTemplate** _string_: the template of input for customize view. Default is `{input}`.

**Example**

`<div class="input-group"><span class="input-group-addon"><i class="fas fa-mobile-alt"></i></span>{input}</div>`

## Input types

Each column in a row can has their own type. Widget supports:

* all yii2 html input types:
  * `textInput`
  * `dropDownList`
  * `radioList`
  * `textarea`
  * For more detail look at [Html helper class](http://www.yiiframework.com/doc-2.0/yii-helpers-html.html)
* input widget \(widget that extends from `InputWidget` class\). For example, `yii\widgets\MaskedInput`
* `static` to output a static HTML content

For using widget as column input you may use the following code:

```php
echo $form->field($model, 'phones')->widget(MultipleInput::className(), [
...
    'columns' => [
        ...
        [
            'name'  => 'phones',
            'title' => $model->getAttributeLabel('phones'),
            'type' => \yii\widgets\MaskedInput::className(),
            'options' => [
                'class' => 'input-phone',
                'mask' => '999-999-99-99',
            ],
        ],
    ],
])->label(false);
```

