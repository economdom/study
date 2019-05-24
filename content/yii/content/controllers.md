# Контроллеры в Yii

В этом уроке мы узнаем как при обращении к конкретному адресу, например *http://yii.loc/index.php?r=site/hello* у нас загружается конкретный класс контроллера, вызывается в нём конкретный метод/экшн, подгружается конкретный вид и в итоге мы видим внешний вид и данные той страницы, которую запрашивали.

Этот механизм реализован благодаря системе роутинга или маршрутизации. В дебагере мы можем видеть текущией роут.

```
Name       | Value
---        |---
Route      | 'site/hello'
Action     | 'app\\controllers\\SiteController::actionHello()'
Parameters | []
```

Вернёмся к запрошенному ULR, а именно к параметрам после *index.php*.

* `?r=` - обозначает что в качестве параметра мы передаем роут
* `site` - это имя контроллера
* `hello` - имя метода/экшена данного контроллера

Подобный механизм используется во многих PHP фреймворках.

Контроллеры должны быть унаследованы от `yii\web\Controller` или его потомков. Название контроллера должно начинаться с большой буквы, а заканчиваться словом `Controller`.

Контроллер состоит из специальных функций - экшены или действия, которые вызываются по определённому адресу. Названия этих экшенов должны состоять из двух слов - `action` и самого названия экшена с большой буквы. Добавляя префикс `action` в начале, мы обозначаем что это именно экшены контроллера, а не другие вспомагательные функции (например `behaviors()`, `actions()`).

При этом метод `actionIndex()` - это экшн по умолчанию и вы можете не указывать явно `index` в качестве сегмента в URL.

Примеры:

* `article` - соответствует `app\controllers\ArticleController`;
* `post-comment` - соответствует `app\controllers\PostCommentController`;
* `admin/post-comment` - соответствует `app\controllers\admin\PostCommentController`;

В папке *views/site* находяться все виды контроллера `SiteController`, то есть каждая папка в *views/* (кроме *layouts/*) пренадлежит конкретному контроллеру. Каждый конкретный вид рекомендуется именовать по имени экшена, хотя это и не объязательно.

*controllers/MyController.php*

```
<?php

namespace app\controllers;

use \yii\web\Controller;

class MyController extends Controller{

    function actionIndex(){
        return $this->render('index');
    }

}
```

Теперь создадим вид:

*views/my/index.php*

```
<h1>Action Index</h1>
```

Обратимся к нашему экшену и проверим результат:

* *http://yii.loc/index.php?r=my/index*

Мы часто будем получать данные из модели и передавать их в вид, но для начала мы создадим эти данные внутри контроллера. Для этого мы создадим переменные (строка, массив) внутри нашего экшена и чтобы передать их вид, нам нужно в качестве второго параметра метода `render()` передать массив, где в качестве ключа мы определяем название переменной по которой данные будут доступны в шаблоне, а в качестве значения мы указываем передаваемые данные.

*controllers/MyController.php*

```
<?php

namespace app\controllers;

use \yii\web\Controller;

class MyController extends Controller{

    function actionIndex(){
        $hi = 'Hello, World!';
        $names = array('Yura', 'Misha', 'Stepan');
        return $this->render('index', ['hello' => $hi, 'names' => $names]);
        // return $this->render('index', compact('hi', 'names'));
    }

}
```

Функция `compact()` позволяет более коротко передавать необходимые данные в вид.

*views/my/index.php*

```
<h1>Action Index</h1>
<?php echo $hello ?>
<pre>
<?php print_r($names) ?>
</pre>
<?php echo $names[0] ?>
<ul>
<?php foreach($names as $name): ?>
    <li><?php echo $name ?></li>
<?php endforeach ?>
</ul>
```

Теперь давайте передадим параметр в контроллер.

*controllers/MyController.php*

```
function actionIndex($id = null){
    $hi = 'Hello, World!';
    $names = array('Yura', 'Misha', 'Stepan');
    return $this->render('index', compact('hi', 'names', 'id'));
}
```

Добавим переменную в вид:

*views/my/index.php*

```
<h1>Action Index</h1>
<?php echo $id ?>
```

Теперь пробуем обращаться к нашему экшену с параметром и без:

* *http://yii.loc/index.php?r=my/index*
* *http://yii.loc/index.php?r=my/index&id=123*

Также может быть необходимость во вложенных контроллерах, например если нам нужно сделать админку.

*controllers/admin/UserController.php*

```
<?php

namespace app\controllers\admin;

use \yii\web\Controller;

class UserController extends Controller{

    public function actionIndex(){
        return $this->render('index');
    }

}
```

Главное не забыть передать правильный `namespace`.

Добавим вид:

*views/admin/user/index.php*

```
<h1>User Index</h1>
```

Обращаемся к нашему контроллеру:

* *http://yii.loc/index.php?r=admin/user/*