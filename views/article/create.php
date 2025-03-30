	<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Article $model */

$this->title = 'Создать статью';
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="section-sm">
	<div class="container">

<div class="article-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'selectedTags'=>$selectedTags,
        'selectedCategory'=>$selectedCategory,
        'tags'=>$tags,
        'categories'=>$categories
    ]) ?>

</div>

	</div>
</section>
