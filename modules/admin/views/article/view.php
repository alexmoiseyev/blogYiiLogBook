<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Article $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Set Image', ['set-image', 'id' => $model->id], ['class' => 'btn btn-default', 'style'=>'border:1px solid black;']) ?>
        <?= Html::a('Set Category', ['set-category', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Set Tags', ['set-tags', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description:ntext',
            'content:ntext',
            'date',
            'image',
            'viewed',
            'user_id',
            'status',
            'category_id',
        ],
    ]) ?>

</div>
