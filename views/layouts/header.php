<?php
use yii\helpers\Html;

?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">ПУ</span><span class="logo-lg">Панель управления</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Min</span>
        </a>
        <div class="navbar-custom-menu">
            <div class="pull-right">
                <?= Html::a('Выход',['/user/logout'],['data-method' => 'post', 'class' => 'btn btn-default btn-flat','id'=>'button_logout']) ?>
            </div>
        </div>
    </nav>
</header>