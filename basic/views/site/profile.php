<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Мой профиль';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (!empty($tasks)): ?>
    <h2>Мои задачи:</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                Задача №<?= $task->id ?><br>
                Название задачи: <?= Html::encode($task->title) ?><br>
                Описание задачи: <?= Html::encode($task->description) ?><br>
                Дата задачи: <?= Html::encode($task->due_date) ?><br>
                Статус задачи: <?= Html::encode($task->status) ?><br>
                <!-- Добавьте другие необходимые данные о заказе -->

                <!-- Check if the task status is 1 before showing the form -->
                <?php if ($task->status == 1): ?>
                    <?php $form = ActiveForm::begin([
                        'action' => ['task/submit-review', 'id' => $task->id],
                        'method' => 'post',
                    ]); ?>
                    <?= Html::submitButton('Отправить на проверку', ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>У вас пока нет задач.</p>
<?php endif; ?>