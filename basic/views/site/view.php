<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\helpers\Html;

$this->title = $user->name;
?>

<h1><?= Html::encode($user->name) ?></h1>
<p>Email: <?= Html::encode($user->email) ?></p>

<p><?= Html::a('Дополнительная информация', ['user/additional-info', 'id' => $user->id], ['class' => 'btn btn-primary']) ?></p>

<h2>Текущие задачи</h2>
<ul>
    <?php foreach ($user->tasks as $task): ?>
        <?php if ($task->status == 'pending'): ?>
            <li><?= Html::encode($task->title) ?> - <?= Html::encode($task->description) ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<h2>Выполненные задачи</h2>
<ul>
    <?php foreach ($user->tasks as $task): ?>
        <?php if ($task->status == 'completed'): ?>
            <li><?= Html::encode($task->title) ?> - <?= Html::encode($task->description) ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
