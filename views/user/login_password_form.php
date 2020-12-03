<?php
use yii\helpers\Html;

$this->title = ($title)? $title : 'Форма авторизации пользователя';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];
?>

<div class="login-box">
    <div class="login-logo">
        <a href="#"><b>Admin PANEL</b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Для работы в административной панели необходимо авторизироваться</p>


        <form method="post" action="<?= Yii::$app->params['basic_url']?>user/login">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken()?>"/>
                    Имя пользователя:
                    <input type="text" name="LoginPasswordForm[user_name]" class="form-control"/><br>
                    Пароль прольвателя:
                    <input type="password" name="LoginPasswordForm[user_password]" class="form-control"/><br>

            </table>



        <div class="row">
            <div class="col-xs-8">

            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
            <!-- /.col -->
        </div>

        </form>


    </div>
    <!-- /.login-box-body -->
</div><!-- /.login-box -->