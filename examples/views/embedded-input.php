<?php

use yii\bootstrap\ActiveForm;
use unclead\widgets\MultipleInput;
use unclead\widgets\examples\models\ExampleModel;
use yii\helpers\Html;
use unclead\widgets\MultipleInputColumn;


/* @var $this \yii\web\View */
/* @var $model ExampleModel */

$commonAttributeOptions = [
    'enableAjaxValidation'   => false,
    'enableClientValidation' => false,
    'validateOnChange'       => false,
    'validateOnSubmit'       => true,
    'validateOnBlur'         => false,
];
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]);?>

<?php

echo MultipleInput::widget([
    'model' => $model,
    'attribute' => 'questions',
    'attributeOptions' => $commonAttributeOptions,
    'columns' => [
        [
            'name' => 'question',
            'type' => 'textarea',
        ],
        [
            'name' => 'answers',
            'type'  => MultipleInput::class,
            'options' => [
                'attributeOptions' => $commonAttributeOptions,
                'columns' => [
                    [
                        'name' => 'right',
                        'type' => MultipleInputColumn::TYPE_CHECKBOX
                    ],
                    [
                        'name' => 'answer'
                    ]
                ]
            ]
        ]
    ],
]);
?>

<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>