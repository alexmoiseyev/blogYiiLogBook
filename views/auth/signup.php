<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Registry';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-comment mr0"><!--leave comment-->
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="site-login">
                <h1><?= Html::encode($this->title) ?></h1>

                <p>Please fill out the following fields to login:</p>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                        'labelOptions' => ['class' => 'col-lg-1 control-label'],
                    ],
                ]); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
                
                <?= $form->field($model, 'email')->textInput() ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="d-flex">
                    <div class="col-lg-offset-1 mr-5">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                    </div>
<!-- Put this div tag to the place, where Auth block will be -->
<div id="vk_auth"></div>
            <script type="text/javascript">
                VK.Widgets.Auth("vk_auth", {width: "200px", authUrl: 'blogyii.localhost/auth/login-vk'});
            </script>
        </div>
                </div>
                
                <?php ActiveForm::end(); ?>
                <div>
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
        </div>
    </div>
</div>