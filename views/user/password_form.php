<?php

$this->title = ($titel) ? $title : 'Форма изменения пароля';

?>

<div class="row content-block-text">
    <div class="col-xs-12" style="margin-left: -15px;"><h2>Логин пользователя: <b><?= $user->identity->user_email ?></b></h2></div>

    <div>
        <form role="form" method="post" action="<?= Yii::$app->params['basic_url']?>user/change-activate-password">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->getCsrfToken()?>"/>
            <div class="form-group">
                <label for="exampleInputEmail1">Введите новый пароль пользователя</label>
                    <input type="text" class="form-control" name="PasswordForm[user_password]" style="width: 400px;">
                </div>
            <button type="submit" class="btn btn-default">Изменить</button>
        </form>
    </div>
</div>