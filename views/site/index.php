<?php 
use yii\helpers\Url;
use yii\widgets\LinkPager;
?>
	<section class="section">
		<div class="container">
			<div class="row">
				 <!-- Сначала сайдбар для мобильных устройств -->
				 <div class="col-lg-4 order-lg-2 mb-5 mb-lg-0 d-block d-lg-none">
					<?= $this->render('/partials/sidebar.php', [
						'categories'=>$categories,
						'tags'=>$tags,
						'articles'=>$articles,
					]);?>
            	</div>
				<div class="col-lg-8  mb-5 mb-lg-0">
					<?php if($articles):?>
						<p class="f-20 <?=$search ?? 'd-none'?>"><?= "Результат поиска: «" . isset($search) . "»" ?? ''?></p>
					<?php foreach($articles as $article):?>
						<p class="<?=$article->isViewed(Yii::$app->user->identity->id) ? '': 'd-none' ?>">просмотренно</p>
					<article class="row mb-5">
						<div class	="col-12">
							<div class="text-center" style="background-color: #f2f2f2;">
								<img loading="lazy" src="<?= $article->getImage();?>" class="img-fluid img-thumbnail" alt="post-thumb" style="max-height:440px;">
							</div>
						</div>
						<div class="col-12 mx-auto">
							<h3><a class="post-title" href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>"><?=$article->title?></a></h3>
							<ul class="list-inline post-meta mb-4">
								<li class="list-inline-item"><i class="ti-user mr-2"></i>
									<a href="<?= Url::toRoute(['site/profile', 'id'=>$article->author->id ?? 'No author link']);?>"><?=$article->author->name ?? 'No author'; ?></a>
								</li>
								<li class="list-inline-item"><?= $article->getDate(); ?></li>
								<li class="list-inline-item">Categories : 
									<a href="<?= Url::toRoute(['site/category','id'=>$article->category_id ?? 3])?>" class="ml-1 <?= isset($selectedCategory) == $article->category_id && isset($selectedCategory) != 0 ? 'text-primary font-weight-bold' : '' ?>"><?= $article->category->title ?? 'No category'; ?></a>
								</li>
								<li class="list-inline-item">Tags:
									<?php foreach($article->tags as $tag): ?>
										<a href="<?= Url::toRoute(['site/tag','id'=>$tag->id])?>" 
										class="ml-1 <?= in_array($tag->id, $selectedTagIds ?? []) ? 'text-primary font-weight-bold' : '' ?>">
											<?= $tag->title ?>
										</a>
									<?php endforeach; ?>
								</li>
							<li class="list-inline-item">Viewed:
								<?=$article->countViews()?>
							</li>
							</ul>
							<p><?=$article->description;?></p> 
							<a href="<?= Url::toRoute(['site/view', 'id'=>$article->id]);?>" class="btn btn-outline-primary">Continue Reading</a>
						</div>
					</article>
					<?php endforeach;?>
					<?php else: ?>
						<p class="">articles with title '<?=$search??0?>' not found</p>
					<?php endif;?>
					<div class="pagination-wrapper">
						<?= LinkPager::widget([
							'pagination' => $pages,
							'options' => ['class' => 'pagination'],
							'linkContainerOptions' => ['class' => 'page-item'],
							'linkOptions' => ['class' => 'page-link'],
							'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
							'maxButtonCount' => 5,
						]) ?>
					</div>
				</div>
			<?= $this->render('/partials/sidebar.php', [
					'categories'=>$categories,
					'tags'=>$tags,
					'articles'=>$articles,
				]);?>
			</div>
		</div>
	</section>