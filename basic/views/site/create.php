<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создать задачу';
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['task']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="task-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'due_date')->input('date') ?>

        <?= $form->field($model, 'status')->dropDownList([
            0 => 'Pending',
            1 => 'Submitted',
            2 => 'Completed',
        ]) ?>

        <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

