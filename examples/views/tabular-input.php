<?php

use unclead\multipleinput\renderers\ListRenderer;
use yii\bootstrap\ActiveForm;
use unclead\multipleinput\TabularInput;
use yii\helpers\Html;
use unclead\multipleinput\examples\models\Item;
use unclead\multipleinput\TabularColumn;


/* @var $this \yii\web\View */
/* @var $models Item[] */
?>

<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'tabular-form',
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]) ?>

<?= TabularInput::widget([
    'models' => $models,
    'modelClass' => Item::class,
    'rendererClass' => ListRenderer::class,
    'min' => 0,
    'layoutConfig' => [
        'offsetClass' => 'col-sm-offset-4',
        'labelClass' => 'col-sm-4',
        'wrapperClass' => 'col-sm-4',
        'errorClass' => 'col-sm-4'
    ],
    'attributeOptions' => [
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnChange' => false,
        'validateOnSubmit' => true,
        'validateOnBlur' => false,
    ],
    'form' => $form,
    'columns' => [
        [
            'name' => 'id',
            'type' => TabularColumn::TYPE_HIDDEN_INPUT
        ],
        [
            'name' => 'title',
            'title' => 'Title',
            'type' => TabularColumn::TYPE_TEXT_INPUT,
            'attributeOptions' => [
                'enableClientValidation' => true,
                'validateOnChange' => true,
            ],
            'defaultValue' => 'Test',
            'enableError' => true
        ],
        [
            'name' => 'description',
            'title' => 'Description',
        ],
//        [
//            'name'  => 'file',
//            'title' => 'File',
//            'type'  => \vova07\fileapi\Widget::className(),
//            'options' => [
//                'settings' => [
//                    'url' => ['site/fileapi-upload']
//                ]
//            ]
//        ],
        [
            'name' => 'date',
            'type'  => \kartik\date\DatePicker::className(),
            'title' => 'Day',
            'options' => [
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true
                ]
            ],
            'headerOptions' => [
                'style' => 'width: 250px;',
                'class' => 'day-css-class'
            ]
        ],
    ],
]) ?>


<?= Html::submitButton('Update', ['class' => 'btn btn-success']); ?>
<?php ActiveForm::end(); ?>