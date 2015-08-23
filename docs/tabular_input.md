# Tabular input

For example you want to have an interface for manage some abstract items via tabular input.

In this case you can use `yii2-multiple-input` widget for supporting tabular input how to describe below.

Our test model can looks like as the following snippet

```php
namespace unclead\widgets\examples\models;

use Yii;
use yii\base\Model;
// you have to install https://github.com/vova07/yii2-fileapi-widget
use vova07\fileapi\behaviors\UploadBehavior;

/**
 * Class Item
 * @package unclead\widgets\examples\models
 */
class Item extends Model
{
    public $title;
    public $description;
    public $file;
    public $date;

    public function behaviors()
    {
        return [
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'file' => [
                        'path' => Yii::getAlias('@webroot') . '/images/',
                        'tempPath' => Yii::getAlias('@webroot') . '/images/tmp/',
                        'url' => '/images/'
                    ],
                ]
            ]
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            ['file', 'safe']
        ];
    }
}
```

Then we have to use `TabularInput` widget for rendering form field in the view file

```php
<?php

use yii\bootstrap\ActiveForm;
use unclead\widgets\TabularInput;
use yii\helpers\Html;
use \unclead\widgets\examples\models\Item;

/* @var $this \yii\web\View */
/* @var $models Item[] */
?>

<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id'                        => 'tabular-form',
    'enableAjaxValidation'      => true,
    'enableClientValidation'    => false,
    'validateOnChange'          => false,
    'validateOnSubmit'          => true,
    'validateOnBlur'            => false,
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]) ?>

<?= TabularInput::widget([
    'models' => $models,
    'attributeOptions' => [
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
        'validateOnChange'          => false,
        'validateOnSubmit'          => true,
        'validateOnBlur'            => false,
    ],
    'columns' => [
        [
            'name'  => 'title',
            'title' => 'Title',
            'type'  => \unclead\widgets\MultipleInputColumn::TYPE_TEXT_INPUT,
        ],
        [
            'name'  => 'description',
            'title' => 'Description',
        ],
        [
            'name'  => 'file',
            'title' => 'File',
            'type'  => \vova07\fileapi\Widget::className(),
            'options' => [
                'settings' => [
                    'url' => ['site/fileapi-upload']
                ]
            ]
        ],
        [
            'name'  => 'date',
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


<?= Html::submitButton('Update', ['class' => 'btn btn-success']);?>
<?php ActiveForm::end();?>
```


Your action can looks like the following code

```php
/**
 * Class TabularInputAction
 * @package unclead\widgets\examples\actions
 */
class TabularInputAction extends Action
{
    public function run()
    {
        Yii::setAlias('@unclead-examples', realpath(__DIR__ . '/../'));

        $models = [new Item()];
        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->post('ajax') !== null) {
            $data = Yii::$app->request->post('Item', []);
            foreach (array_keys($data) as $index) {
                $models[$index] = new Item();
            }
            Model::loadMultiple($models, Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result = ActiveForm::validateMultiple($models);
            return $result;
        }

        if (Model::loadMultiple($models, Yii::$app->request->post())) {
            // your magic
        }


        return $this->controller->render('@unclead-examples/views/tabular-input.php', ['models' => $models]);
    }
}
```
