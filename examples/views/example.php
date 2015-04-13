<?php

use yii\bootstrap\ActiveForm;
use unclead\widgets\Button;
use unclead\widgets\MultipleInput;
use unclead\widgets\examples\models\ExampleModel;

/* @var $this \yii\base\View */
/* @var $model ExampleModel */
?>

<?php $form = ActiveForm::begin([
    'enableAjaxValidation'      => false,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
]);?>

<?= $form->field($model, 'emails')->widget(MultipleInput::className(), [
    'limit' => 2,
 ]);
?>
<?= Button::update();?>
<?php ActiveForm::end();?>