# Вывод Hello, World!

Для этого в файле контроллера создадим функцию и по правилам именования функций контроллера названия каждой функции должно начинаться со слова `action`:

*controllers/SiteController.php*

```
public function actionHello(){
    return "Hello, World!";
}
```

Теперь чтобы вызвать эту функцию нам нужно перейти по адресу *http://yii.loc/index.php?r=site/hello*, где `site` - это название нашего контроллера `SiteController`, а `hello`, это название функции экшена внутри данного контроллера `actionHello()`.

Пока мы просто возвращаем строку на белом экране, но чтобы вывести строку внутри шаблона (как сделанно это на других страницах) нам нужно вызвать функцию `render()` и передать ей название файла вида в папке *views/site/*:

*controllers/SiteController.php*

```
public function actionHello(){
    return $this->render('hello');
}
```

Создадим простой вид:

*views/site/hello.php*

```html
<h1>Hello, World!</h1>
```

![Вывод строки "Hello, World!" в Yii2](https://github.com/kamuz/study/blob/master/content/yii/content/img/hello-world.png?raw=true)

Как работает эта магия мы узнаем в следующем уроке.