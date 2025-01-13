<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
?>

<section class="section">
    <div class="container">
        <div class="article-form">
        
            <?php $form = ActiveForm::begin(); ?>
        
            <?= Html::dropDownList('tags', $selectedTags, $tags,['class'=>'form-control', 'multiple'=>true]) ?>
        
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
        
        </div>
        
    </div>
</section>