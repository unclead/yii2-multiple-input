#Yii2 Multiple input widget.
Yii2 widget for handle multiple inputs for an attribute of model and tabular input for batch of models.

[![Latest Stable Version](https://poser.pugx.org/unclead/yii2-multiple-input/v/stable)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Total Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/downloads)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Daily Downloads](https://poser.pugx.org/unclead/yii2-multiple-input/d/daily)](https://packagist.org/packages/unclead/yii2-multiple-input)
[![Latest Unstable Version](https://poser.pugx.org/unclead/yii2-multiple-input/v/unstable)](https://packagist.org/packages/unclead/yii2-multiple-input) 
[![License](https://poser.pugx.org/unclead/yii2-multiple-input/license)](https://packagist.org/packages/unclead/yii2-multiple-input)

##Latest release
The latest stable version of the extension is v1.3.1. Follow the [instruction](./UPGRADE.md) for upgrading from previous versions

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

##Basic usage

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

You can find more examples of usage [here](./docs/usage.md)

##Documentation

- [Configuration](./docs/configuration.md)
- [Usage](./docs/usage.md)
- [Tips and tricks](./docs/tips.md)
- [Javascript Events and Operations](./docs/javascript.md)
- [Renderers](./docs/renderers.md)

##License

**yii2-multiple-input** is released under the BSD 3-Clause License. See the bundled [LICENSE.md](./LICENSE.md) for details.
