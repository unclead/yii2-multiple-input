<?php

namespace unclead\widgets\examples\actions;

use Yii;
use yii\base\Action;
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

        if ($model->load(Yii::$app->request->post())) {
            if (!$model->validate()) {
                var_dump($model->getErrors());
            }

        }
        return $this->controller->render('@unclead-examples/views/example.php', ['model' => $model]);
    }
}