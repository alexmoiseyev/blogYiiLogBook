<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<aside class="col-lg-4">
   <div class="widget">
      <h5 class="widget-title"><span>Search</span></h5>
      <form action="<?= Url::to(['/site/search'])?>" class="widget-search">
         <input id="search-query" name="search" type="search" placeholder="Type &amp; Hit Enter...">
         <button type="submit"><i class="ti-search"></i>
         </button>
      </form>
   </div>
   <!-- categories -->
   <div class="widget">
      <h5 class="widget-title"><span>Categories</span></h5>
      <ul class="list-unstyled widget-list">
         <?php foreach($categories as $category):?>
            <?php if($category->getArticlesCount() > 0):?>
               <li><a href="<?= Url::toRoute(['site/category','id'=>$category->id])?>" class="d-flex"><?=$category->title?></a>
               <span class="post-count pull-right"> (<?= $category->getArticlesCount();?>)</span>
            </li>
            <?php endif; ?>
         <?php endforeach;?>
      </ul>
   </div>
   <!-- tags -->
   <div class="widget">
      <h5 class="widget-title"><span>Tags</span></h5>
      <ul class="list-inline widget-list-inline">
         <?php foreach($tags as $tag):?>
            <li class="list-inline-item">
               <a href="<?= Url::toRoute(['site/tag','id'=>$tag->id])?>">
                  <?=$tag->title;?>
               </a>
            </li>
         <?php endforeach;?>
      </ul>
   </div>
   <!-- latest post -->
   <div class="widget">
      <h5 class="widget-title"><span>Latest Article</span></h5>
      <?php foreach($latestArticles as $latestArticle):?>
      <ul class="list-unstyled widget-list">
         <li class="media widget-post align-items-center">
            <a href="<?= Url::toRoute(['site/view','id'=>$latestArticle->id])?>">
               <img loading="lazy" class="mr-3" src="<?=$latestArticle->getImage();?>">
            </a>
            <div class="media-body">
               <h5 class="h6 mb-0"><a href="<?= Url::toRoute(['site/view','id'=>$latestArticle->id])?>"><?= $latestArticle->title ?></a></h5>
               <small><?= $latestArticle->getDate();?></small>
            </div>
         </li>
      </ul>
      <?php endforeach;?>
   </div>
			</aside>