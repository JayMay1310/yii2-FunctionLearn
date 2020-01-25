<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

    $this->registerJs(
        "$('input[type=\"checkbox\"]').click(function(){

            var thischeck = $(this);

            if ( thischeck.is(':checked') ) {

                var currentRow = $(this).closest('tr');
                var count = currentRow.find('td:eq(5)').text();
                thischeck.parents().addClass('danger');
                if (count % 5 == 0) {                   
                    var myVar = setTimeout(myTimer, 60000);
                }
                else
                {                   
                    var myVar = setTimeout(myTimer, 30000);
                }
            } else {          
               thischeck.parents().removeClass('danger');
               thischeck.parents().removeClass('success');
            }

            function myTimer() {
              thischeck.parents().removeClass('danger');             
              thischeck.parents().addClass('success');
            }

          });"
    );

/* @var $this yii\web\View */
/* @var $model common\models\Category */
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<?=Html::beginForm(['function/learn'],'post');?>

<?=Html::dropDownList('action','',['learn'=>'Выучил',],['class'=>'dropdown',])?>

<?=GridView::widget([
    'options' => ['class' => 'table-responsive'],
    'tableOptions' => ['class' => 'table table-condensed'],
    'dataProvider' => $dataProvider,
    'rowOptions'=>function($model, $key, $index, $column){
        
    },
    'columns' => [
      [
         'class' => 'yii\grid\CheckboxColumn',
         'checkboxOptions' => function($model, $key, $index, $widget) {
            return ['value' => $model['id'] ];
          },
        ],
        ['class' => 'yii\grid\SerialColumn',],
        [
            'attribute' => 'function',
            'value' => function ($data) {
                return Html::a(Html::encode($data->function), $data->link, ['target'=>'_blank']);
            },                
            'format' => 'raw',
        ],
        'value', 'last_update', 'count',
       [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{update} {delete}',
        'buttons' => [
            'update' => function ($url, $model, $key) {
                 return Html::a('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', ['function/update', 'id' => $model->id]);
            },

            'delete' => function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>', ['function/delete', 'id' => $model->id]);
            },
        ],
    ],
      ],
  ]); ?>

<?=Html::submitButton('Выучил', ['class' => 'btn btn-info',]);?>

<?= Html::endForm();?> 