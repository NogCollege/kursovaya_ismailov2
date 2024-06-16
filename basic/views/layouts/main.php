<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>


<header id="header">
        <nav>
            <div class="container menu">
                <ul class="site-navigation">
                    <li><a  href="<?= Url::to(['site/profile']) ?>">Мой профиль</a></li>
                    <li><a href="">Новости</a></li>
                    <li><a href="">Расписание</a></li>
                    <li><a href="">Отделы</a></li>
                    <li><a href="">Контакты</a></li>
                </ul>
                <a  href="<?= Url::to(['site/login']) ?>">Войти</a>
                <a  href="<?= Url::to(['/site/logout'], ['data-method' => 'post']) ?>">Выход</a>
                <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasRole('manager')): ?>
                    <li><?= Html::a('Сделать запись', ['/site/task']) ?></li>
                <?php endif; ?>
            </div>
        </nav>
        <figure class="main-header-logo">
            <a>
                <h1 class="ctulh">Ктулху</h1>
            </a>
        </figure>
    </header>
<main id="main" class="flex-shrink-0" role="main">
    <div>
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
    <footer class="podval">
        <main class="container">
            <p>
                Управление персоналом и рабочими процессами ООО ОАО "Ни одного сайта подобного не встретил."<br>
                Адрес: г.Махачкала ул.Гаджиева<br>
                <a href="">Как нас найти</a><br>
                Телефон: 8 (977) 966-95-94
            </p>
            <p>
                РАЗРАБОТАНО:<br>
                <button class="razrab">Мною</button>
            </p>
        </main>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

