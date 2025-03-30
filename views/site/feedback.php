<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<section class="section-sm">
	<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="content mb-5">
						<h1 id="ask-us-anything-br-or-just-say-hi">Ask Us Anything <br> Or just Say Hi,</h1>
						<p>Rather than just filling out a form, Sleeknote also offers help to the user
							<br>with links directing them to find additional information or take popular actions.</p>
						<h4 class="mt-5">Hate Forms? Write Us Email</h4>
						<p><i class="ti-email mr-2 text-primary"></i><a href="mailto:georgia.young@example.com">companyname@gmail.com</a>
						</p>
					</div>
				</div>
				<div class="feedback-form col-md-6">

					<?php $form = ActiveForm::begin(); ?>

					<?= $form->field($model, 'message')->textInput(['maxlength' => true]) ?>

					<div class="form-group">
						<?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
					</div>

					<?php ActiveForm::end(); ?>

				</div>
			</div>
		</div>
	</div>
</section>