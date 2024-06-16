<?php


use yii\helpers\Html;
use yii\grid\GridView;
use app\models\task;

$this->title = 'Задачи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать задачу', ['assign-task'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Выставить прогул', ['leave'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Назначить дополнительную работу', ['extra-work'], ['class' => 'btn btn-warning']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'description:ntext',
            'due_date',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Task::getStatusLabels()[$model->status];
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>


