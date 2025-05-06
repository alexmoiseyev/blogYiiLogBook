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
   <div class="widget">
      <h5 class="widget-title"><span>Categories & Tags</span></h5>
      <div class="dropdown position-relative">
         <button class="btn btn-secondary dropdown-toggle w-100 text-center" type="button" 
                  id="filterDropdown" data-toggle="dropdown" 
                  aria-haspopup="true" aria-expanded="false">
            Фильтровать статьи
         </button>
         <div class="dropdown-menu w-100" aria-labelledby="filterDropdown" 
               style="max-height: 300px; overflow-y: auto;">
            <form action="<?= yii\helpers\Url::to(['site/multiple-search']) ?>" method="get">
               <label>Категория:</label>
               <?= Html::dropDownList(
                     'category_id',  
                     null,
                     ArrayHelper::map($categories, 'id', 'title'),
                     [
                        'multiple' => false,
                        'size' => 5,
                        'class' => 'form-control'
                     ]
               ) ?>

               <!-- Выбор тегов (множественный) -->
               <label class="mt-3">Теги:</label>
               <?= Html::dropDownList(
                     'tag_ids[]',  // name с [] для множественного выбора
                     null,
                     ArrayHelper::map($tags, 'id', 'title'),
                     [
                        'multiple' => true,
                        'size' => 5,
                        'class' => 'form-control'
                     ]
               ) ?>
               
               <button type="submit" class="btn btn-primary mt-3 w-100">Поиск</button>
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