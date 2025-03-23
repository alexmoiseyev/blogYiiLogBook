<?php 
   use yii\helpers\Url; 
   use yii\helpers\Html;
?>
<!DOCTYPE html>

<html lang="en-us">

<head>
   <meta charset="utf-8">
   <title>Logbook - Homepage</title>

   <!-- mobile responsive meta -->
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
   <meta name="description" content="This is meta description">
   <meta name="author" content="Themefisher">
 
   <!-- theme meta -->
   <meta name="theme-name" content="logbook" />

</head>

<body>
<header class="sticky-top bg-white border-bottom border-default">
   <div class="container">

      <nav class="navbar navbar-expand-lg navbar-white">
         <a class="navbar-brand" href="/site/index">
            <img class="img-fluid" width="150px" src="/markup/images/logo.png" alt="LogBook">
         </a>
         <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navigation">
            <i class="ti-menu"></i>
         </button>

         <div class="collapse navbar-collapse text-center" id="navigation">
            <ul class="navbar-nav ml-auto">
               <li class="nav-item dropdown">
                  <a class="nav-link" role="button" href="/site/index">
                     homepage </i>
                  </a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="/create">Create Article</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="/contact">Contact</a>
               </li>
               <?php if(!Yii::$app->user->isGuest):?>
                  <li class="nav-item">
                     <a class="nav-link" href="<?= Url::toRoute(['/profile', 'id'=>Yii::$app->user->identity->id ?? '0'] )?>">Profile</a>
                  </li>
               <?php endif; ?>
               </li>
            </ul>
            
            <div class="i_con">
                    <ul class="nav navbar-nav d-flex ">
                        <?php if(Yii::$app->user->isGuest):?>
                            <li><a href="<?= Url::toRoute(['auth/login'])?>">Login</a></li>
                            /
                            <li><a href="<?= Url::toRoute(['auth/signup'])?>">Register</a></li>
                        <?php else: ?>
                            <?= Html::beginForm(['/auth/logout'], 'post')
                            . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->name . ')',
                                ['class' => 'btn btn-link logout', 'style'=>"padding-top:10px;"]
                            )
                            . Html::endForm() ?>
                        <?php endif;?>
                    </ul>
                </div>
            <!-- search -->
            <div class="search px-4">
               <button id="searchOpen" class="search-btn"><i class="ti-search"></i></button>
               <div class="search-wrapper">
                  <form action="<?= Url::to(['/site/search'])?>" class="h-100">
                     <input class="search-box pl-4" id="search-query" name="search" type="search" placeholder="Type &amp; Hit Enter...">
                     <button type="submit"><i class="ti-search"></i></button>
                  </form>
                  <button id="searchClose" class="search-close"><i class="ti-close text-dark"></i></button>
               </div>
            </div>

         </div>
      </nav>
   </div>
</header>