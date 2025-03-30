<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title', )->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
        
    <?= $form->field($model, 'image')->fileInput(['maxlength' => true, 'id' => 'image-input'])->label('Добавьте изображение:') ?>
    <div>
        <p>Выберите категорию для статьи:</p>
        <?= Html::dropDownList('category', $selectedCategory, $categories, ['class'=>'form-control']) ?>
    </div>
    <hr>
    <div>
        <p>Выберите теги для статьи:</p>
        <?= Html::dropDownList('tags', $selectedTags, $tags,['class'=>'form-control', 'multiple'=>true]) ?>
    </div>
    <hr>
    <div class="form-group">
        <?= Html::submitButton('Создать статью', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
