<?php

namespace unclead\multipleinput\components;

use yii\helpers\ArrayHelper;
use yii\web\View;

class JsCollector
{
    const POSITIONS_ORDER = [View::POS_HEAD, View::POS_BEGIN, View::POS_END, View::POS_READY, View::POS_LOAD];

    /**
     * @var View
     */
    private $view;

    /**
     * @var array
     */
    private $jsExclude = [];

    /**
     * @var array
     */
    private $jsInit = [];

    /**
     * @var array
     */
    private $jsTemplates = [];

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function onBeforeRender()
    {
        if (!is_array($this->view->js)) {
            return;
        }

        $this->jsExclude = [];
        foreach ($this->view->js as $position => $scripts) {
            foreach ((array)$scripts as $key => $js) {
                if (!isset($this->jsExclude[$position])) {
                    $this->jsExclude[$position] = [];
                }

                $this->jsExclude[$position][$key] = $js;
            }
        }
    }

    public function onAfterRender()
    {
        if (!is_array($this->view->js)) {
            return;
        }

        foreach (self::POSITIONS_ORDER as $position) {
            foreach (ArrayHelper::getValue($this->view->js, $position, []) as $key => $js) {
                if (isset($this->jsExclude[$position][$key])) {
                    continue;
                }

                $this->jsExclude[$position][$key] = $js;

                $this->jsInit[$key] = $js;

                unset($this->view->js[$position][$key]);
            }
        }
    }

    public function onAfterPrepareTemplate()
    {
        if (!is_array($this->view->js)) {
            return;
        }

        $this->jsTemplates = [];

        foreach (self::POSITIONS_ORDER as $position) {
            foreach (ArrayHelper::getValue($this->view->js, $position, []) as $key => $js) {
                if (isset($this->jsExclude[$position][$key])) {
                    continue;
                }

                $this->jsTemplates[$key] = $js;

                unset($this->view->js[$position][$key]);
            }
        }

    }

    /**
     * @return array
     */
    public function getJsInit()
    {
        return $this->jsInit;
    }

    /**
     * @return array
     */
    public function getJsTemplates()
    {
        return $this->jsTemplates;
    }
}
