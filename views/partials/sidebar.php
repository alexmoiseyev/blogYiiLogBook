<?php

use app\models\Article;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
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
   <div class="dropdown position-relative"> <!-- Добавлено position-relative -->
      <button class="btn btn-secondary dropdown-toggle w-100 text-center" type="button" 
               id="categoriesDropdown" data-toggle="dropdown" 
               aria-haspopup="true" aria-expanded="false">
         Выберите категорию
      </button>
      <div class="dropdown-menu w-100" aria-labelledby="categoriesDropdown" 
            style="max-height: 300px; overflow-y: auto;"> <!-- Добавлены стили для прокрутки -->
         <?php foreach($categories as $category): ?>
         <?php if($category->getArticlesCount() > 0): ?>
            <a class="dropdown-item d-flex justify-content-between align-items-center" 
               href="<?= Url::toRoute(['site/category','id'=>$category->id]) ?>">
               <?= $category->title ?> 
               <span class="badge badge-light"><?= $category->getArticlesCount() ?></span>
            </a>
         <?php endif; ?>
         <?php endforeach; ?>
      </div>
   </div>
   </div>
   <!-- tags -->
   <div class="widget">
    <h5 class="widget-title"><span>Tags</span></h5>
    <div class="dropdown position-relative"> <!-- Добавлено position-relative -->
      <button class="btn btn-secondary dropdown-toggle w-100 text-center" type="button" id="categoriesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         Выберите теги
      </button>
      <div class="dropdown-menu w-100" aria-labelledby="categoriesDropdown" style="max-height: 300px; overflow-y: auto;">
            
      <form action="<?= yii\helpers\Url::to(['site/tag-multiple']) ?>" method="get">

         <?= Html::dropDownList(
               'tag_ids',
               null,
               ArrayHelper::map($tags, 'id', 'title'),
               [
                  'multiple' => true,
                  'size' => 5,
                  'class' => 'form-control'
               ]
         ) ?>
         
         <button type="submit" class="btn btn-primary mt-2">Поиск</button>
      </form>
      </div>
   </div>
</div>
   <!-- latest articles -->
   <div class="widget d-none d-lg-block">
      <h5 class="widget-title"><span>Latest Article</span></h5>
      <?php foreach(Article::getLatestArticles() as $latestArticle):?>
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