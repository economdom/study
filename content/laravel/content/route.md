# Основы роутинга и контроллеры

Мы можем использовать разные запросы GET, POST, DELETE и это позволяет очень просто создавать RESTful API. Роутинг по синтаксису похож на то что мы имеем в Express/Node.js, то есть мы указываем тип запроса, потом указываем URL или правило роутинга, после чего используем функцию обратного вызова, чтобы указать что именно должно проиходить.

*routes/web.php*

```
Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return "<h1>Hello, world!</h1>";
});
```

Вы можете создавать вложенные шаблоны, поэтому давайте создадим:

*resources/views/pages/about.blade.php*

```
<h1>About page</h1>

<?php

echo "Hello, man";
```

Теперь создадим роут:

*routes/web.php*

```
Route::get('/about', function () {
    return view('pages.about');
});
```

Можно использовать точку для обращения к файлу в директории, а можно более привычный слэш.

Мы можем передавать динамические данные, взятые с сегмента URL

*routes/web.php*

```
Route::get('/users/{id}', function ($id) {
    return "Hi, $id";
});
```

Чтобы передать больше переменных нужно использовать такой синтаксис:

*routes/web.php*

```
Route::get('/users/{id}/{name}', function ($id, $name) {
    return "This is user $name with an ID of $id";
});
```

В большинстве случаев роут не вызывает вид на прямую - как правило это делает контроллер. Чтобы создать контроллер мы будем использовать консольную утилиту Artisan, для этого переходим в корень приложения и в консоли пишем:

```
php artisan make:controller PagesController
```

Имя контроллера принято называть с большой буквы и каждое следующее слово также должно быть с большой буквы без пробелов. Можно создавать имена в множественном числе, а можно в единичном - как вам будет удобней.

Теперь если мы посмотрим в папку *app/Http/Controllers/* мы увидим наш файл с таким содержимым:

*app/Http/Controllers/PagesController.php*

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
}
```

Создадим метод *index()*  и просто будем возвращать строку:

*app/Http/Controllers/PagesController.php*

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return "INDEX CONTROLLER";
    }
}
```

Теперь нам больше не нужна функция обратного вызова, а обращения к контроллеру и его методу выполняется таким образом:

*routes/web.php*

```
Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');
```

В большинстве случаев нужно рендерить шаблон, поэтому мы создадим новый шаблон *pages/index.blade.php* и передадим его в метод `view()` в контроллере.

*app/Http/Controllers/PagesController.php*

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        return view('pages.index');
    }

    public function about(){
        return view('pages.about');
    }

    public function services(){
        return view('pages.services');
    }
}
```

Теперь осталось создать необходимые виды, например:

*resources/views/pages/index.blade.php*

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel APP') }}</title>
</head>
<body>

<h1>Welcome to our site</h1>

</body>
</html>
```

В `title` мы выводим информацию поля `name` из файла `.env`, а если там ничего нет, тогда установим значение по умолчанию.