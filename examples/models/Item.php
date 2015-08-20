<?php

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
            [['title', 'description'], 'required']
        ];
    }


}