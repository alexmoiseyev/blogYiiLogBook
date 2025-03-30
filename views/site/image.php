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
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'id' => 'avatar-form' // Добавляем ID формы
            ]); ?>

            <?= $form->field($model, 'image')->fileInput(['maxlength' => true, 'id' => 'image-input']) ?>

            <div id="image-preview" style="display: none;">
                <img id="preview-image" src="" alt="Image Preview" style="max-width: 100%;">
            </div>

            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'id' => 'submit-button', 'disabled' => true]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</section>

<!-- Включите Cropper.js и его стили -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
    let cropper; // To store the Cropper instance

    document.getElementById('image-input').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function () {
            const imageUrl = reader.result;
            const previewImage = document.getElementById('preview-image');
            previewImage.src = imageUrl;
            document.getElementById('image-preview').style.display = 'block';

            // Initialize cropper
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(previewImage, {
                aspectRatio: 1, // Set as a square aspect ratio
                viewMode: 1,
            });

            // Enable the submit button when an image is selected
            document.getElementById('submit-button').disabled = false;
        };

        reader.readAsDataURL(file);
    });

    // Обработка отправки формы
    document.getElementById('avatar-form').addEventListener('submit', function (e) {
        e.preventDefault();
        
        if (!cropper) {
            alert('Пожалуйста, выберите изображение');
            return;
        }

        // Get cropped canvas data
        const canvas = cropper.getCroppedCanvas({
            width: 256,
            height: 256,
        });

        if (!canvas) {
            alert('Ошибка при обрезке изображения');
            return;
        }

        // Convert the canvas to blob
        canvas.toBlob(function (blob) {
            const formData = new FormData();
            formData.append('ImageUpload[image]', blob, 'avatar.png');

            // Отправляем данные на сервер
            fetch('<?= Yii::$app->urlManager->createUrl(['profile/set-avatar', 'id' => Yii::$app->user->id]) ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-Token': '<?= Yii::$app->request->csrfToken ?>' // CSRF токен
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    if (data.url) {
                        window.location.href = data.url; // Редирект после успешной загрузки
                    }
                } else {
                    alert(data.message || 'Ошибка при загрузке аватара');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при отправке данных');
            });
        }, 'image/png');
    });
</script>