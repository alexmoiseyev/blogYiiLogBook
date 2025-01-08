<?php
use yii\helpers\Url;
?>
<section class="section">
	<div class="container">
		<article class="row mb-4">
			<div class="col-lg-10 mx-auto mb-4">
				<h1 class="h2 mb-3"><?= $article->title;?></h1>
				<ul class="list-inline post-meta mb-3">
					<li class="list-inline-item"><i class="ti-user mr-2"></i><a href="<?= Url::toRoute(['site/author', 'id'=>$article->author->id ?? 'No author link']);?>">
					<?=$article->author->name ?? 'No author'; ?></a>
					</li>
					<li class="list-inline-item"><?= $article->getDate();?></li>
					<li class="list-inline-item">Categories : <a href="<?= Url::toRoute(['site/category','id'=>$article->category->id ])?>" class="ml-1"><?= $article->category->title; ?></a>
					</li>
					<li class="list-inline-item">Tags : 
						<?php foreach($tags as $tag):?>
                        	<a href="<?= Url::toRoute(['site/tag','id'=>$tag->id])?>" class="ml-1"><?= $tag->title; ?></a>
                     	<?php endforeach; ?>
					</li>
				</ul>
			</div>
			<div class="col-12 mb-3">
				<div class="post-slider">
					<img style="height:880px;" src="<?=$article->getImage();?>" class="img-fluid" alt="post-thumb">
				</div>
			</div>
			<div class="col-lg-10 mx-auto">
				<div class="content">
					<p><?= $article->title; ?></p>
				</div>
			</div>
		</article>
	</div>
</section>
