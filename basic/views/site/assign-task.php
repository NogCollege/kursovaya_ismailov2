<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Назначить задачу';
$this->params['breadcrumbs'][] = ['label' => 'Задачи', 'url' => ['task']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="task-assign">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="task-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'due_date')->input('date') ?>
        <?= $form->field($model, 'user_id')->dropDownList($userList, ['prompt' => 'Выберите исполнителя']) ?>
        <?= $form->field($model, 'department_id')->dropDownList($departmentList, ['prompt' => 'Выберите отдел']) ?>
        <?= $form->field($model, 'status')->dropDownList(['1' => 'Активная', '2' => 'Выполнена']) ?>

        <div class="form-group">
            <?= Html::submitButton('Назначить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>


        <?= Html::errorSummary($model) ?>


    </div>
</div>
