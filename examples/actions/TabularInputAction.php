<?php
/**
 * Created by PhpStorm.
 * User: unclead_2
 * Date: 15.08.2015
 * Time: 18:34
 */

namespace unclead\widgets\examples\actions;

use Yii;
use unclead\widgets\examples\models\Item;
use yii\base\Action;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

class TabularInputAction extends Action
{
    public function run()
    {
        Yii::setAlias('@unclead-examples', realpath(__DIR__ . '/../'));

        $count = count(Yii::$app->request->post('Item', []));
        $models = [new Item()];
        for($i = 1; $i < $count; $i++) {
            $models[] = new Item();
        }

        $request = Yii::$app->getRequest();
        if ($request->isPost && $request->post('ajax') !== null) {
            Model::loadMultiple($models, Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            $result = ActiveForm::validateMultiple($models);
            return $result;
        }

        if (Model::loadMultiple($models, Yii::$app->request->post())) {
            // put here your logic
        }


        return $this->controller->render('@unclead-examples/views/tabular.php', ['models' => $models]);
    }
}