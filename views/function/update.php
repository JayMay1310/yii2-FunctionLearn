<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Category */

?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
    ]) ?>

</div>