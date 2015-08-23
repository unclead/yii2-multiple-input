#Multiple columns example

![Multiple columns example](./images/multiple-column.gif?raw=true)

For example you want to have an interface for manage user schedule. For simplicity we will store the schedule in json string.

In this case you can use `yii2-multiple-input` widget for supporting multiple inputs how to describe below.

Our test model can looks like as the following snippet

```php
class ExampleModel extends Model
{
    public $schedule;

    public function init()
    {
        parent::init();

        $this->schedule = [
            [
                'day'       => '27.02.2015',
                'user_id'   => 1,
                'priority'  => 1
            ],
            [
                'day'       => '27.02.2015',
                'user_id'   => 2,
                'priority'  => 2
            ],
        ];
    }
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
            ]
        ],
        [
            'name'  => 'priority',
            'title' => 'Priority',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ]
    ]
 ]);
?>
<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>
```


For validation the schedule you can use the following code

```php

    public function validateSchedule($attribute)
    {
        $requiredValidator = new RequiredValidator();

        foreach($this->$attribute as $index => $row) {
            $error = null;
            $requiredValidator->validate($row['priority'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][priority]';
                $this->addError($key, $error);
            }
        }
    }
```
