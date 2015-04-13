<?php

namespace unclead\widgets\assets;

use yii\web\AssetBundle;

/**
 * Class MultipleInputAsset
 * @package unclead\widgets\assets
 */
class MultipleInputAsset extends AssetBundle
{
    public $css = [
        'css/multiple-input.css'
    ];

    public $js = [
        'js/jquery.multipleInput.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets/src/';
        parent::init();
    }


} 