<?php 
use yii\helpers\Url; 
?>
<section class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-8  mb-5 mb-lg-0">
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
							<a href="<?= Url::toRoute(['site/author', 'id'=>$article->id ?? 3]);?>"><?=$article->author->name ?? 'No author';?></a>
							</li>
							<li class="list-inline-item"><?= $article->getDate(); ?></li>
							<li class="list-inline-item">Categories : <a href="<?= Url::toRoute(['site/category','id'=>$article->category->id])?>" class="ml-1"><?= $article->category->title; ?></a>
							</li>
                     <li class="list-inline-item">Tags:
							<?php foreach($article->tags as $tag):?>
                        	<a href="<?= Url::toRoute(['site/tag','id'=>$tag->id])?>" class="ml-1"><?= $tag->title; ?></a>
                     	<?php endforeach; ?>
                        </li>
						</ul>
						<p><?=$article->description;?></p> 
                        <a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>" class="btn btn-outline-primary">Continue Reading</a>
					</div>
				</article>
                <?php endforeach;?>
			</div>
			<?= $this->render('/partials/sidebar.php', [
				'latestArticles'=>$latestArticles,
                'categories'=>$categories,
                'tags'=>$tags,
               'article'=>$article
            ]);?>
		</div>
	</div>
</section>