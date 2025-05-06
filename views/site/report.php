<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Report $model */
/** @var yii\widgets\ActiveForm $form */
?>
<section class="section-sm">
   <div class="container">
        <h1>Жалоба на пользователя: <?= $user->name?></h1>
        <div class="report-form col-md-6">
        
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($reportModel, 'title')->textInput(['maxlength' => true]) ?>
        
            <?= $form->field($reportModel, 'text')->textInput(['maxlength' => true]) ?>
        
            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
        
        </div>
   </div>
</section>
