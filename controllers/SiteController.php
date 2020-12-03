<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;



class SiteController extends BasicController
{
    /**
     * {@inheritdoc}
     */
    public $city_id;
    public $city_name;


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Отдельные классы экшинов
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Главная страница
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionPage($id=FALSE)
    {
        return $this->render('page',['id'=>$id]);
    }

}
