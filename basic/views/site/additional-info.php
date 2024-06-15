<?php
/* @var $this yii\web\View */
/* @var $user app\models\User */

use yii\helpers\Html;

$this->title = 'Дополнительная информация: ' . $user->name;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>Здесь будет дополнительная информация о пользователе.</p>
<p>Имя: <?= Html::encode($user->name) ?></p>
<p>Email: <?= Html::encode($user->email) ?></p>