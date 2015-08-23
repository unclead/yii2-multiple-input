#Single column example

![Single column example](./images/single-column.gif?raw=true)

For example your application contains the model `User` that has the related model `UserEmail` 
You can add virtual attribute `emails` for collect emails from form and then you can save them to database. 

In this case you can use `yii2-multiple-input` widget for supporting multiple inputs how to describe below.

First of all we have to declare virtual attribute in model

```php
class ExampleModel extends Model
{
    /**
     * @var array virtual attribute for keeping emails
     */
    public $emails;
```

Then we have to use `MultipleInput` widget for rendering form field in the view file

```php
use yii\bootstrap\ActiveForm;
use unclead\widgets\MultipleInput;
use unclead\widgets\examples\models\ExampleModel;
use yii\helpers\Html;

/* @var $this \yii\base\View */
/* @var $model ExampleModel */
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]);?>

<?= $form->field($model, 'emails')->widget(MultipleInput::className(), [
    'limit' => 4,
 ]);
?>
<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>
```

Options `limit` means that user able to input only 4 emails

For validation emails you can use the following code

```php
    /**
     * Email validation.
     *
     * @param $attribute
     */
    public function validateEmails($attribute)
    {
        $items = $this->$attribute;

        if (!is_array($items)) {
            $items = [];
        }

        foreach ($items as $index => $item) {
            $validator = new EmailValidator();
            $error = null;
            $validator->validate($item, $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . ']';
                $this->addError($key, $error);
            }
        }
    }
```
