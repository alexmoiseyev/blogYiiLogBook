<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrationForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Registry';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register d-flex justify-content-center align-items-center p-5">
    <div class="col-md-6">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>Please fill out the following fields to register:</p>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-7 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <div>
                <?= Html::submitButton('Register', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <div id="vk_auth"></div>
        <script type="text/javascript">
            VK.Widgets.Auth("vk_auth", {width: "200px", authUrl: 'blogyii.localhost/auth/login-vk'});
        </script>

        <script src="https://unpkg.com/@vkid/sdk@<3.0.0/dist-sdk/umd/index.js"></script>
        <script type="text/javascript">
            if ('VKIDSDK' in window) {
                const VKID = window.VKIDSDK;

                VKID.Config.init({
                    app: 52838969,
                    redirectUrl: 'https://blogyii.localhost/auth/login-vk',
                    responseMode: VKID.ConfigResponseMode.Callback,
                    source: VKID.ConfigSource.LOWCODE,
                    scope: '', // Заполните нужными доступами по необходимости
                });

                const floatingOneTap = new VKID.FloatingOneTap();

                floatingOneTap.render({
                    appName: 'BlogYii2',
                    showAlternativeLogin: true
                })
                .on(VKID.WidgetEvents.ERROR, vkidOnError)
                .on(VKID.FloatingOneTapInternalEvents.LOGIN_SUCCESS, function (payload) {
                    const code = payload.code;
                    const deviceId = payload.device_id;

                    VKID.Auth.exchangeCode(code, deviceId)
                        .then(vkidOnSuccess)
                        .catch(vkidOnError);
                });

                function vkidOnSuccess(data) {
                    floatingOneTap.close();
                    // Обработка полученного результата
                }

                function vkidOnError(error) {
                    // Обработка ошибки
                }
            }
        </script>
    </div>
</div>