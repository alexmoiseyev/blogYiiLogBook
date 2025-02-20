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
					<h1 class="h4"><?= $user->name ?></h1>
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
			
				<img loading="lazy" class="rounded-lg img-fluid" src="<?= $user->getImage()??'/markup/images/author.jpg'?>">
					<?php if(!Yii::$app->user->isGuest):?>
						<?php if($_GET['id'] == Yii::$app->user->identity->id):?>
							<a href="<?= Url::toRoute(['article/set-avatar', 'id'=>$user->id]);?>" class="ml-1 ">
								<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-file-earmark-image rounded mx-auto d-block" viewBox="0 0 16 16">
								<path d="M6.502 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
								<path d="M14 14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zM4 1a1 1 0 0 0-1 1v10l2.224-2.224a.5.5 0 0 1 .61-.075L8 11l2.157-3.02a.5.5 0 0 1 .76-.063L13 10V4.5h-2A1.5 1.5 0 0 1 9.5 3V1z"/>
								</svg></a>
						<?php endif;?>
					<?php endif;?>
			</div>
			<div class="col-lg-9 col-md-8 content text-center text-md-left">
				<h1> About me: </h1>
				<p>Lorem ipsum sit amet, consectetur adipiscing elit. Proin sit amet vulputate augue. Duis auctor lacus id vehicula gravida. Nam suscipit vitae purus et laoreet. Donec nisi dolor, consequat vel pretium id, auctor in dui. Nam iaculis, neque ac ullamcorper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet vulputate augue. Duis auctor lacus id vehicula gravida. Nam suscipit vitae purus et laoreet.</p>
				<p>Donec nisi dolor, consequat vel pretium id, auctor in dui. Nam iaculis, neque ac ullamcorper.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sit amet vulputate augue.</p>
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
						<a href="<?= Url::toRoute(['author', 'id'=>Yii::$app->user->identity->id]);?>" class="btn btn-primary">My articles</a>
						<a href="<?= Url::toRoute(['history', 'id'=>Yii::$app->user->identity->id]);?>" class="btn btn-outline-warning">History</a>
					</div>
				<?php endif;?>
			<?php endif;?>
                <?php foreach($articles as $article):?>
				<article class="row mb-5">
					<div class="col-12">
						<div class="text-center">
							<img loading="lazy" src="<?= $article->getImage();?>" class="img-fluid img-thumbnail" alt="post-thumb" style="height:440px;">
						</div>
					</div>
					<div class="col-12 mx-auto">
						<h3><a class="post-title" href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>"><?=$article->title?></a></h3>
						<ul class="list-inline post-meta mb-4">
							<li class="list-inline-item"><i class="ti-user mr-2"></i>
								<a href="<?= Url::toRoute(['site/author', 'id'=>$article->author->id]);?>"><?=$article->author->name;?></a>
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
							<?=$article->countViews();?>
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
				<hr>
                <?php endforeach;?>
			</div>
		</div>
</section>
