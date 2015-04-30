<?php

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

<h3>Single column</h3>
<?= $form->field($model, 'emails')->widget(MultipleInput::className(), [
        'limit' => 5
    ])
    ->label(false);
?>


<h3>Multiple columns</h3>
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
?>
<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>