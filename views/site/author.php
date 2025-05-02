<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<style>
        .avatar {
            width: 150px;
            height: 150px;
			border: 2px solid black;
            border-radius: 50%; /* Чтобы сделать изображение круглым */
            object-fit: cover; /* Обеспечивает обрезку изображения по размеру */
        }
    </style>

<section class="section-sm border-bottom">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4"><?= $user->name ?></h1>
					<ul class="list-inline social-icons ml-auto mr-3 d-none d-sm-block">
						<!-- <li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a>
						</li>
						<li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a> -->
						</li>
						<li class="list-inline-item"><a href="#"><i class="ti-github"></i></a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
					<?php if(!Yii::$app->user->isGuest):?>
						<?php if($_GET['id'] == Yii::$app->user->identity->id):?>
							<a href="<?= Url::toRoute(['profile/set-avatar', 'id'=>$user->id]);?>" class="ml-1 active">
								<img loading="lazy" class="rounded-circle img-fluid avatar" src="<?= $user->getImage()??'/markup/images/author.jpg'?>">
							</a>
						<?php else: ?>
							<img loading="lazy" class="rounded-circle img-fluid avatar" src="<?= $user->getImage()??'/markup/images/author.jpg'?>">
						<?php endif;?>
					<?php endif;?>
			</div>
			<div class="col-lg-9 col-md-8 content text-center text-md-left">
				<h1> About me: </h1>
				<p><?= Html::encode($user->about) ?></p>
				<?php if($_GET['id'] == Yii::$app->user->identity->id):?>
				<?=Html::button('изменить описание',[
					'class'=>'btn btn-primary',
					'id'=>'toggle-description'
				])?>
				<?php endif;?>
				<?php if (!$user->isFollower(Yii::$app->user->identity->id)):?>
					<a href="<?=Url::toRoute(['/profile/subscribe', 'id'=>$user->id]);?>" class="btn btn-light">Подписаться</a>	
				<?php else:?>
					<a href="<?=Url::toRoute(['/profile/unsubscribe', 'id'=>$user->id]);?>" class="btn">Отписаться</a>	
				<?php endif?>
					<div class="mt-2">
						<p>Подписки: <?= $user->countSubcriptions();?></p>
						<p>Подписчики: <?= $user->countFollowers();?></p>	
					</div>
				<div class="about-form d-none mt-5" >
					<?php $form = ActiveForm::begin(['action' => ['profile/set-profile-description']]); ?>
						<?= $form->field($user, 'about')->textarea(['rows' => 6]) ?>
						<div class="form-group">
							<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
			</div>
	</div>
	
</section>

<section class="section">
		<div class="d-flex justify-content-center">
			<div class="col-lg-8 mb-5 mb-lg-0">
			<?php if(!Yii::$app->user->isGuest):?>
				<?php if($_GET['id'] == Yii::$app->user->identity->id):?>
					<div class="d-flex justify-content-center mb-20">
						<a href="<?= Url::toRoute(['/profile', 'id'=>Yii::$app->user->identity->id]);?>" class="btn <?= $_SERVER['REQUEST_URI'] == '/profile?id='.Yii::$app->user->identity->id ? 'btn-primary' : 'btn-outline-primary'?>">My articles</a>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="<?= Url::toRoute(['/profile/history', 'id'=>Yii::$app->user->identity->id]);?>" class="btn <?= $_SERVER['REQUEST_URI'] == '/history?id='.Yii::$app->user->identity->id ? 'btn-secondary' : 'btn-outline-secondary'?>">History</a>
					</div>
				<?php endif;?>
			<?php endif;?>
                <?php foreach($articles as $article):?>
				<article class="row mb-5">
					<div class="col-12">
						<div class="text-center">
							<img loading="lazy" src="<?= $article->getImage();?>" class="img-fluid img-thumbnail" alt="post-thumb" style="max-height:440px;">
						</div>
					</div>
					<div class="col-12 mx-auto">
						<h3><a class="post-title" href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>"><?=$article->title?></a></h3>
						<ul class="list-inline post-meta mb-4">
							<li class="list-inline-item"><i class="ti-user mr-2"></i>
								<a href="<?= Url::toRoute(['/site/author', 'id'=>$article->author->id]);?>"><?=$article->author->name;?></a>
							</li>
							<li class="list-inline-item"><?= $article->getDate(); ?></li>
							<li class="list-inline-item">Categories : <a href="<?= Url::toRoute(['site/category','id'=>$article->category->id??'0'])?>" class="ml-1"><?= $article->category->title??'No category'; ?></a>
							</li>
                     <li class="list-inline-item">Tags:
							<?php foreach($article->tags as $tag):?>
                        	<a href="<?= Url::toRoute(['/site/tag','id'=>$tag->id])?>" class="ml-1"><?= $tag->title; ?></a>
                     	<?php endforeach; ?>
                        </li>
						<li class="list-inline-item">Viewed:
							<?=$article->countViews();?>
						</li>
						</ul>
						<p><?=$article->description;?></p> 
                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>" class="btn btn-outline-primary mb-3">Continue Reading</a>
						<?php if(!Yii::$app->user->isGuest):?>
							<div class="d-flex justify-content-center">
								<?php if(Yii::$app->user->identity->name == $article->author->name):?>
									<?= Html::a('Delete', ['article/delete', 'id' => $article->id], [
										'class' => 'btn btn-danger',
										'data' => [
											'confirm' => 'Are you sure you want to delete this item?',
											'method' => 'post',
										],
									]) ?>
									<?= Html::a('Set Image', ['article/set-image', 'id' => $article->id], ['class' => 'btn btn-warning',]) ?>
									<?= Html::a('Set Tags', ['article/set-tags', 'id' => $article->id], ['class' => 'btn btn-info',]) ?>
								<? endif;?>
							</div>
						<? endif;?>
					</div>
				</article>
				<hr>
                <?php endforeach;?>
			</div>
		</div>
</section>
<script>
document.getElementById('toggle-description').addEventListener('click', function(e) {
    e.preventDefault(); 

    var form = document.querySelector('.about-form');
    
    form.classList.toggle('d-none');
});
</script>

