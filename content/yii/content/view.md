# Виды и шаблоны

Любой динамический сайт содержит постоянные или неизменные части (хедер, футер, сайдбар) и переменные части (контент). В шаблоне у нас будет постоянная информация, а в видах переменная.

Шаблоны Yii находятся в папке *views/layouts/*.

Создадим новый шаблон:

*views/basic.php*

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basic Template</title>
</head>
<body>
    <h1>Basic Template</h1>
</body>
</html>
```

Назначим его глобвально для всего проекта:

*config/web.php*

```
$config = [
    'layout' => 'basic',
    //..
```

Для того чтобы можно было отдельный шаблон назначать для определённой группы страниц, можно указывать это в контроллере в качестве публичного свойства `$layout`, но можно это определить отдельной страницы:

*controllers/PostController.php*

```
<?php

namespace app\controllers;

use \yii\web\Controller;

class PostController extends Controller{

    public $layout = 'basic';

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionShow(){
        $this->layout = 'main';
        return $this->render('show');
    }

}
```

Какие файлы стилей и скриптом нужно подключать определяются в файле *assets/AppAsset.php* и для этого у нас есть несколько массивов. По умолчанию файлы стилей и скриптов должны загружаться с папки *web/*

* `$css` - набор CSS файлов
* `$js` - набор JavaScript файлов
* `$depends` - список зависимости, которые загружаются перед стилями и скриптами от которых они зависят

Создадим и подключим файлы *js/main.js* и *css/main.css*:

*assets/AppAsset.php*

```
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/main.css'
    ];
    public $js = [
        'js/main.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
```

Теперь в шаблоне нам нужно импортировать пространство имён `use app\assets\AppAssets`, так строка `AppAsset::register($this)`, регистрирующая объект `$this`, которым мы можем дальше использовать.

Кроме этого нам понадобятся метки `$this->beginPage()`, `$this->head()`, `$this->beginBody()`, `$this->endBody()`, `$this->endPage()`, с помощью которых мы указываем Yii где именно нужно подключать стили и скрипты.

Чтобы вывести контент, который мы определяем в видах нужно использовать переменную `$content`.

*views/layouts/main.php*

```
<?php
use app\assets\AppAsset;
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
    <?php echo $content ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
```

За справкой всегда можно обратиться к уже созданным шаблонам.