<?php

namespace unclead\widgets\examples\actions;

use Yii;
use yii\base\Action;
use yii\bootstrap\ActiveForm;
use yii\web\Response;
use unclead\widgets\examples\models\ExampleModel;

/**
 * Class MultipleInputAction
 * @package unclead\widgets\examples\actions
 */
class MultipleInputAction extends Action
{
    public function run()
    {
        Yii::setAlias('@unclead-examples', realpath(__DIR__ . '/../'));

        $model = new ExampleModel();

        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->post('ajax') !== null) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

        }
        return $this->controller->render('@unclead-examples/views/example.php', ['model' => $model]);
    }
}