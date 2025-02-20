<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Article $model */
/** @var yii\widgets\ActiveForm $form */
?>

<section class="section">
    <div class="container">
        <p class="h3">Выберите изображение для вашего профиля:</p>
        <div class="article-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'image')->fileInput(['maxlength' => true, 'id' => 'image-input']) ?>

    <div id="image-preview" style="display: none;">
        <img id="preview-image" src="" alt="Image Preview" style="max-width: 200px;">
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
    </div>
</section>
<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function() {
            document.getElementById('preview-image').src = reader.result;
            document.getElementById('image-preview').style.display = 'block';
        };

        reader.readAsDataURL(file);
    });
</script>
