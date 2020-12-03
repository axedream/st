<?php
namespace app\controllers;


use yii\web\Controller;
use Yii;
use yii\web\Response;
use app\models\user\User;

class BasicController extends Controller
{
    /**
     * Сообщение об ошибке
     *
     * @var
     */
    public $msg;
    /**
     * Наличие ошибки
     *
     * @var
     */
    public $error;

    /**
     * Код ошибки
     *
     * @var int
     */
    public $error_type = 0;


    /**
     * Данные к выдаче
     *
     * @var
     */
    public $data;

    /**
     * Работа с ссессиями
     *
     * @var
     */
    public $session;

    /**
     * Группы текущего пользователя [array]
     *
     * @var
     */
    public $aGroups = FALSE;

    /**
     * Модель текущего пользователя [object]
     *
     * @var
     */
    public $oUser = FALSE;

    /**
     * IP адресс пользователя
     *
     * @var bool
     */
    public $sUser_ip = FALSE;

    /**
     * Добавляем работу с группами
     *
     * @return bool
     */


    public function getGroupeRole()
    {
        $this->sUser_ip = Yii::$app->request->userIP;
        if (!Yii::$app->user->isGuest && empty($this->aGroups) && empty($this->oUser) ){
            $this->oUser = User::findOne(Yii::$app->user->identity->id);
            $this->aGroups = User::getGroupeUser($this->oUser->id);
        }
        return TRUE;
    }

    /**
     * Первичная инициализация для всех контроллеров
     */
    public function init()
    {
        parent::init();
        if (isset(Yii::$app->session)) {
            $this->session = Yii::$app->session;
            if (!$this->session->isActive) {
                $this->session->open();
            }        }

        $this->getGroupeRole();
    }

    //---------------------------------------------------- AJAX ----------------------------------------//
    /**
     * Стандартная выдача сообщений
     *
     * @return array
     */
    public function out()
    {
        return ['error'=>$this->error, $this->error_type,'msg'=>$this->msg, 'data'=> ($this->error=='no') ? $this->data : '' ];
    }

    /**
     * Базовая инициализация
     */
    public function init_ajax()
    {
        $this->error = 'yes';
        $this->msg = Yii::$app->params['messages']['user']['error']['params'];
        Yii::$app->response->format = Response::FORMAT_JSON;
    }
    //---------------------------------------------------- END AJAX ----------------------------------------//


}