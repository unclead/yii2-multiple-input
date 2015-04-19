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

<?= $form->field($model, 'emails')->widget(MultipleInput::className(), [
    'limit' => 4,
 ]);
?>
<?php
//    echo $form->field($model, 'phones')->widget(MultipleInput::className(), [
//        'limit' => 4,
//    ]);
?>
<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>