<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\User;
?>
<section class="section-sm border-bottom">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="title-bordered mb-5 d-flex align-items-center">
					<h1 class="h4"><?= $name ?></h1>
					<ul class="list-inline social-icons ml-auto mr-3 d-none d-sm-block">
						<li class="list-inline-item"><a href="#"><i class="ti-facebook"></i></a>
						</li>
						<li class="list-inline-item"><a href="#"><i class="ti-twitter-alt"></i></a>
						</li>
						<li class="list-inline-item"><a href="#"><i class="ti-github"></i></a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-lg-3 col-md-4 mb-4 mb-md-0 text-center text-md-left">
			
				<img loading="lazy" class="rounded-lg img-fluid" src="<?= $user->getImage()??'/markup/images/author.jp'?>">
					<?php if(!Yii::$app->user->isGuest):?>
						<?php if($_GET['id'] == Yii::$app->user->identity->id):?>
							<a href="<?= Url::toRoute(['article/set-avatar', 'id'=>$user->id]);?>"><?='change avatar'?></a>
						<?php endif;?>
					<?php endif;?>
			</div>
			<div class="col-lg-9 col-md-8 content text-center text-md-left">
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet vulputate augue. Duis auctor lacus id vehicula gravida. Nam suscipit vitae purus et laoreet. Donec nisi dolor, consequat vel pretium id, auctor in dui. Nam iaculis, neque ac ullamcorper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet vulputate augue. Duis auctor lacus id vehicula gravida. Nam suscipit vitae purus et laoreet.</p>
				<p>Donec nisi dolor, consequat vel pretium id, auctor in dui. Nam iaculis, neque ac ullamcorper.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet vulputate augue.</p>
			</div>
			<section class="section">
	<div class="container">
		<div class="row d-flex justify-content-center">
			<div class="col-lg-8  mb-5 mb-lg-0">
			<?php if(!Yii::$app->user->isGuest):?>
				<?php if($_GET['id'] == Yii::$app->user->identity->id):?>
					<div class="text-center">
						<a href="<?= Url::toRoute(['author', 'id'=>Yii::$app->user->identity->id]);?>" class="btn btn-primary">My articles</a>
						<a href="<?= Url::toRoute(['history', 'id'=>Yii::$app->user->identity->id]);?>" class="btn btn-outline-warning">History</a>
					</div>
				<?php endif;?>
			<?php endif;?>
                <?php foreach($articles as $article):?>
				<article class="row mb-5">
					<div class	="col-12">
						<div class="text-center">
							<img loading="lazy" src="<?= $article->getImage();?>" class="img-fluid img-thumbnail" alt="post-thumb" style="height:440px;">
						</div>
					</div>
					<div class="col-12 mx-auto">
						<h3><a class="post-title" href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>"><?=$article->title?></a></h3>
						<ul class="list-inline post-meta mb-4">
							<li class="list-inline-item"><i class="ti-user mr-2"></i>
								<a href="<?= Url::toRoute(['site/author', 'id'=>$article->id]);?>"><?=$article->author->name;?></a>
							</li>
							<li class="list-inline-item"><?= $article->getDate(); ?></li>
							<li class="list-inline-item">Categories : <a href="<?= Url::toRoute(['site/category','id'=>$article->category->id??'0'])?>" class="ml-1"><?= $article->category->title??'No category'; ?></a>
							</li>
                     <li class="list-inline-item">Tags:
							<?php foreach($article->tags as $tag):?>
                        	<a href="<?= Url::toRoute(['site/tag','id'=>$tag->id])?>" class="ml-1"><?= $tag->title; ?></a>
                     	<?php endforeach; ?>
                        </li>
						<li class="list-inline-item">Viewed:
							<?=$article->getUsers()->count();?>
						</li>
						</ul>
						<p><?=$article->description;?></p> 
                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>" class="btn btn-outline-primary">Continue Reading</a>
						<?php if(!Yii::$app->user->isGuest):?>
							<?php if(Yii::$app->user->identity->name == $article->author->name):?>
								<?= Html::a('Delete', ['delete', 'id' => $article->id], [
									'class' => 'btn btn-danger',
									'data' => [
										'confirm' => 'Are you sure you want to delete this item?',
										'method' => 'post',
									],
								]) ?>
								<?= Html::a('Set Image', ['article/set-image', 'id' => $article->id], ['class' => 'btn btn-default', 'style'=>'border:1px solid black;']) ?>
								<?= Html::a('Set Tags', ['article/set-tags', 'id' => $article->id], ['class' => 'btn btn-default', 'style'=>'border:1px solid black;']) ?>
							<? endif;?>
						<? endif;?>
					</div>
				</article>
                <?php endforeach;?>
			</div>
		</div>
	</div>
</section>
		</div>
	</div>
	
</section>
