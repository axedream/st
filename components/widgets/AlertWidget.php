<?php
namespace app\components\widgets;

use Yii;
use kartik\alert\Alert;

/**
 * Всплывающие мессаджи
 *
 * Class AlertWidget
 * @package app\components\widgets
 */
class AlertWidget extends \yii\bootstrap\Widget
{
    public $session;


    /**
     * Инициализация виджета
     */
    public function init()
    {
        if (isset(Yii::$app->session)   ) {
            $this->session = Yii::$app->session;
            if (!$this->session->isActive) {
                $this->session->open();
            }
        }
    }

    /**
     * Типы сообщений
     *
     * @var array
     */
    public $alertTypes = [
        'error'   => Alert::TYPE_DANGER,
        'danger'  => Alert::TYPE_DANGER,
        'success' => Alert::TYPE_SUCCESS,
        'info'    => Alert::TYPE_INFO,
        'warning' => Alert::TYPE_WARNING,

    ];

    /**
     * Типы иконок
     *
     * @var array
     */
    public $alertIcon = [
        'error'     =>  'glyphicon glyphicon-remove-sign',
        'danger'    =>  'glyphicon glyphicon-ban-circle',
        'success'   =>  'glyphicon glyphicon-ok-sign',
        'info'      =>  'glyphicon glyphicon-question-sign',
        'warning'   =>  'glyphicon glyphicon-question-sign',
    ];




    /**
     * Отображение виджета
     *
     * @return string|void
     * @throws \Exception
     */
    public function run()
    {
        $flashes = $this->session->getAllFlashes();
        foreach ($flashes as $type => $flash) {
            if (!isset($this->alertTypes[$type])) {
                continue;
            }
            foreach ((array) $flash as $i => $message) {
                echo Alert::widget([
                    'type' => $this->alertTypes[$type],
                    'icon' => $this->alertIcon[$type],
                    'body' => $message,
                    'showSeparator' => true,
                    'delay' => 2000
                ]);
            }
            $this->session->removeFlash($type);
        }
    }
}