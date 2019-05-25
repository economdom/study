# Меню

Скопируем меню Bootstrap и для генерации ссылок воспользуемся хелпером `HTML` и его статическим методом `a()`.

*views/layouts/basic.php*

```
<?php
use app\assets\AppAsset;
use yii\helpers\Html;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Template</title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <div class="container">
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><?php echo Html::a('Index', ['/site/index']) ?></li>
                <li role="presentation"><?php echo Html::a('Articles', ['/post/index']) ?></li>
                <li role="presentation"><?php echo Html::a('Post', ['/post/show']) ?></li>
            </ul>
            <?php echo $content ?>
            <?php $this->endBody() ?>
        </div>
    </div>
</body>
</html>
<?php $this->endPage() ?>
```

Не забываем подключить хелпер `Html` перед его использованием.