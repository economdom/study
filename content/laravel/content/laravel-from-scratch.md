# CMS на базе Laravel

* [Настройка окружения и установка Laravel](#setup)
* [Основы роутинга и контроллеры](#route)
* [Blade шаблонизация и компиляция активов](#blade)
* [Модели и миграции базы данных](#model)
* [Получение данных с использованием ORM Eloquent](#eloquent)
* [Установка Font Awesome](#fa)
* [Формы и сохранение данных](#form)
* [Редактирование данных](#edit)
* [Удаление данных](#del)
* [Авторизация пользователей](#auth)
* [Связи моделей](#rel)
* [Контроль доступа](#access)
* [Загрузка файлов](#files)

<div id="setup"></div>

## Настройка окружения и установка Laravel

Нужно установить XAMPP с поддержкой PHP 7, Git, Composer. Если не ставиться Composer качаем *.phar* архив и пользуемся через консольный PHP.

Переходим в папку *htdocs* XAMPP, запускаем терминал и устанавливаем Laravel с помощью Composer:

```
composer create-project laravel/laravel laracms.loc
```

Вам нужно только изменить название папки, в моём же случае название папки совпадает с названием желаемого хоста или локального доменного имени.

Чтобы запустить приложения нам нужно ввести в адресную строку примерно такой адрес:

```
http://localhost/laracms.loc/public/
```

Чтобы поправить это, нужно изменить файл хостов Apache, который в случае с XAMPP находится *apache/conf/extra/httpd-vhosts.conf* - нам нужно указать путь к файлам сайта и название домена:

*apache/conf/extra/httpd-vhosts.conf*

```
<VirtualHost *:80>
    DocumentRoot "D:/Sandbox/htdocs/laracms.loc/public"
    ServerName laracms.loc
</VirtualHost>
```

Также нужно поправить файл хостов:

*c:/Windows/System32/drivers/etc/hosts*

```
127.0.0.1  laracms.loc
```

Чтобы сохранить этот файл нужно открывать его с правами администратора.

Перезапускаем Apache и обращаеся к сайту уже по более короткому адресу:

```
laracms.loc
```

Посмотрим на файловую структуру:

* *app/* - содержит модели, по умолчанию мы имеет только одну модель *Users.php*. Если у вас планируется много моделей, то можно создать подпапку *Models*
* *app/Http/Controllers/* - контроллеры приложения. По умолчанию у нас есть дефолтный котроллер и папка *Auth*, которая содержит контроллеры, которые отвечают за авторизацию и регистрацию пользователей.
* *resources/views/* - содержит все виды приложения. Как правило используется шаблонизатор Blade
* *routes/web.php* - файл с роутами
* *.env* - файл конфигурации, например название проекта, параметры подключения к БД и т. д.

<div id="route"></div>

## Основы роутинга и контроллеры

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

<div id="blade"></div>

## Blade шаблонизация и компиляция активов

Мы можем определить основной шаблон, который будет наследоваться остальными, то есть остальные будут его расширять и использовать общие блоки, но с возможностью их переоределения.

*resources/views/layouts/app.blade.php*

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel APP') }}</title>
</head>
<body>

@yield('content')

</body>
</html>
```

В том месте где у нас встречается `@yield` мы можем определять контент, который нам нужно переоределить. Теперь нужно поправить остальные шаблоны и привести их к таком виду:

*resources/views/pages/index.blade.php*

```
@extends('layouts.app')

@section('content')
<h1>Welcome to our site</h1>
@endsection
```

С помощью `@extend` мы говорим о том что данный шаблон наследует указанный, а конструкция `@section` определяет контент, который мы будем вставлять в том месте, где вызвали определённый `@yield`.

Давайте теперь передадим переменную в наш вид, который будет определять заголовок страницы.

*app/Http/Controllers/PagesController.php*

```
class PagesController extends Controller
{
    public function index(){
        $title = "Welcome to Laravel Application";
        return view('pages.index', compact('title'));
    }
    //..
```

Функция `compact()` служит для короткой записи переменной, которую нужно передать в вид.

Выводить переменную в шаблон следует таким образом:

*resources/views/pages/index.blade.php*

```
@extends('layouts.app')

@section('content')
<h1>{{ $title }}</h1>
@endsection
```

Другой вариант передачи переменной в шаблон - это использование метода `with()`, на вход которого вы должны определить название переменной, которое вы хотите использовать в виде, а вторым параметром данные, которые должны находится в этой переменной. Метод `compact()` более короткий, который использует название переданной переменной в шаблоне, а в методе `with()` это можно переопределить, хотя в большинстве случаев этого не требуется.

*app/Http/Controllers/PagesController.php*

```
    //..
    public function about(){
        $title = "Some info about Us";
        return view('pages.about')->with('title', $title);
    }
    //..
```

Кроме этого вы можете использовать и обычный вывод PHP вместо синтаксиса Laravel, например так:

*resources/views/pages/index.blade.php*

```
@extends('layouts.app')

@section('content')
<h1><?php echo $title; ?></h1>
@endsection
```

Если вам нужно передать несколько значений в вид, то вы можете использовать для этих целей ассоциативный массив.

*app/Http/Controllers/PagesController.php*

```
    //..
    public function services(){
        $data = array(
            'title' => 'Our services',
            'services' => ['Web Desing', 'HTML Coding', 'WordPress Theme Development', 'WordPress Plugin Development']
        );
        return view('pages.services')->with($data);
    }
    //..
```

В шаблоне мы используем условие `if` и цикл `foreach`:

*resources/views/pages/services.blade.php*

```
@extends('layouts.app')

@section('content')
<h1>{{$title}}</h1>
@if(count($services) > 0)
    <ul>
        @foreach($services as $service)
            <li>{{$service}}</li>
        @endforeach
    </ul>
@endif
@endsection
```

По умолчанию Laravel использует Boostrap и скомпилированный файл CSS находится в папке *public/css/app.css* и для того чтобы подключить этот файл в шаблоне нужно использовать:

*resources/views/layouts/app.blade.php*

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel APP') }}</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@yield('content')

<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
```

В папке *resources/* содержатся исходники стилей и скриптов, которые компилируются в версию для продакшена. В файле *resources/sass/app.scss*, то мы увидим от куда у нас импортируются стили. В том числе импорт есть из папки `@import '~bootstrap/scss/bootstrap';"` - это путь к папке *node_modules*, которой у нас пока что нет, поэтому нам нужно запустить команду `npm install`, чтобы поставить все необходимые зависимости в проект.

И если мы посмотрим в файл *package.json* в корне приложения, то мы увидим список зависимостей, которые будут установленны вместе с Bootstrap, например `laravel-mix` будет делать компиляцию стилей и скриптов, а `vue` - это JS библиотека, которая используется для создания интерфейсов и одностраничных приложений.

Запускаем команду

```
npm install
```

Чтобы поставить все зависимости из файла *package.json*.

Теперь мы можем изменять исходники и компилировать стили, для этого можем поправить одну переменную:

*resources/sass/_variables.scss*

```
$body-bg: red;
```

Запускаем `laravel-mix` командой

```
npm run dev
```

Чтобы скомпилировать исходные файлы активов.

Теперь нажимаем <kbd>Ctrl</kbd> + <kbd>F5</kbd> для того чтобы обновить страницу и почистить кэш.

Если вы не хотите запускать команду компиляции каждый раз при изменении исходников, вы можете использовать другую команду:

```
npm run watch
```

Которая будет ослеживать изменения в исходных файлах и автоматичести компилировать их.

Если вам нужно создать ваш собственный файл стилей, то вы можете создать файл:

*resources/sass/_custom.scss*

```
body{
    background-color: blue !important;
}
```

Теперь нужно импортировать данный файл:

*resources/sass/app.scss*

```
// Custom
@import "custom";
```

Теперь можем добавить меню, для этого будем использовать конструкцию `@include`

*resources/views/layouts/app.blade.php*

```
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <title>{{config('app.name', 'Laravel APP')}}</title>
</head>
<body>

@include('include.navbar')
//..
```

Теперь создадим необходимый файл и добавим разметку:

*resources/views/include/navbar.blade.php*

```
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="/">{{config('app.name', 'Laravel APP')}}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/services">Services</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
```

Давайте также добавим блок в котором будут ссылки на авторизацию и регистрацию:

*resources/views/pages/index.blade.php*

```
@extends('layouts.app')

@section('content')
<div class="jumbotron text-center">
    <div class="container">
        <h1>Hello, world!</h1>
        <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
        <p><a class="btn btn-primary btn-lg" href="login" role="button">Login</a> <a class="btn btn-success btn-lg" href="register" role="button">Register</a></p>
    </div>
</div>
<h1>{{$title}}</h1>
@endsection
```

<div id="model"></div>

## Модели и миграции базы данных

В самом начале нам нужно создать БД и пользователя (если нужно).

Затем нам нужно создать модель `Post` и миграцию для неё:

```
php artisan make:model Post -m
```

Мы увидим что у нас создалась модель *app/Post.php* и файл миграций *database/migrations/2019_01_25_225715_create_posts_table.php*.

В методе `up()` класса `CreatePostTable` используется статический метод `create()` объекта `Schema` в котором мы описываем поля, которые нам необходимо создать. Нам нужно добавить несколько полей:

*database/migrations/2017_11_30_183740_create_posts_table.php*

```
class CreatePostsTable extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->mediumText('body');
            $table->timestamps();
        });
    }
    //..
```

В методе `down()` описывается что должно происходить при откате миграции - по умолчанию у нас удаляется текущая таблица в БД.

Вы также должны заметить что у нас уже существуют миграции, которые создадут для нас таблицу с пользователями и сброса пароля.

Перед тем как запускать миграции нам в начале нужно настроить подключение к БД в файле *.env*.

*.env*

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laracms
DB_USERNAME=root
DB_PASSWORD=
```

Если сейчас запустить миграцию, то мы вероятней всего получим следующую ошибку:

```
php artisan migrate
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table

   Illuminate\Database\QueryException  : SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes (SQL: alter table `users` add unique `users_email_unique`(`email`))

  at D:\Server\domains\laracms.loc\vendor\laravel\framework\src\Illuminate\Database\Connection.php:664
    660|         // If an exception occurs when attempting to run a query, we'll format the error
    661|         // message to include the bindings with SQL, which will make this exception a
    662|         // lot more helpful to the developer instead of just the database's errors.
    663|         catch (Exception $e) {
  > 664|             throw new QueryException(
    665|                 $query, $this->prepareBindings($bindings), $e
    666|             );
    667|         }
    668|

  Exception trace:

  1   PDOException::("SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 767 bytes")
      D:\Server\domains\laracms.loc\vendor\laravel\framework\src\Illuminate\Database\Connection.php:458

  2   PDOStatement::execute()
      D:\Server\domains\laracms.loc\vendor\laravel\framework\src\Illuminate\Database\Connection.php:458

  Please use the argument -v to see more details.
```

Чтобы решить эту проблему нужно поправить файл:

*app/Providers/AppServiceProvider.php*

```
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
    //..
}
```

Теперь можно запустить миграцию:

```
php artisan migrate
```

В итоге у нас должно появится 4 таблицы в БД. Теперь нам нужно добавить в таблицу данные, чтобы можно было дальше продолжить работу. Это можно сделать вручную, а можно использовать консольную утилиту `tinker`:

* Запускаем `tinker`
* Проверяем количество записей в таблице `post`
* Создаём экземпляр объекта `Post`
* Устанавливаем `title` и `body` для нашего объекта
* Сохраняем запись в БД

```
php artisan tinker
Psy Shell v0.8.15 (PHP 7.1.11 — cli) by Justin Hileman
>>> App\Post::count()
=> 0
>>> $post = new App\Post();
=> App\Post {#737}
>>> $post->title = 'Post One';
=> "Post One"
>>> $post->body = 'This is the post body';
=> "This is the post body"
>>> $post->save();
=> true
>>> $post = new App\Post();
=> App\Post {#748}
>>> $post->title = 'Post Two';
=> "Post Two"
>>> $post->body = 'Another body for second post';
=> "Another body for second post"
>>> $post->save();
=> true
```

В большинстве случаев всегда требуется несколько методов при работе с какими-либо объектами:

* `index` - список всех записей
* `create` - создание записи
* `store` - сохранение записи в БД
* `edit` - редактирование записи
* `update` - сохранение обновлённой записи
* `show` - отобразить конкретную запись
* `destroy` - удалить запись

Чтобы не создавать эти методы самостоятельно, можно этот процес автоматизировать с помощью команды:

```
php artisan make:controller PostsController --resource
```

Как видим все эти методы уже создались за нас автоматически.

Теперь осталось разобраться с роутами. Мы можем посмотреть список доступных роутов с помощью команды:

```
php artisan route:list
+--------+----------+----------+------+-----------------------------------------------+--------------+
| Domain | Method   | URI      | Name | Action                                        | Middleware   |
+--------+----------+----------+------+-----------------------------------------------+--------------+
|        | GET|HEAD | /        |      | App\Http\Controllers\PagesController@index    | web          |
|        | GET|HEAD | about    |      | App\Http\Controllers\PagesController@about    | web          |
|        | GET|HEAD | api/user |      | Closure                                       | api,auth:api |
|        | GET|HEAD | services |      | App\Http\Controllers\PagesController@services | web          |
+--------+----------+----------+------+-----------------------------------------------+--------------+
```

И мы видим список всех роутов, которые были созданны нами и даже те которые создавались системой автоматически и на данным момент не активны.

Чтобы не писать несколько роутов для нашего ресурса, мы можем использовать следующий код:

*routes/web.php*

```
Route::resource('posts', 'PostsController');
```

И если мы сейчас проверим список роутов, то мы убедимся что их именно столько, сколько нам нужно:

```
php artisan route:list
+--------+-----------+-------------------+---------------+-----------------------------------------------+--------------+
| Domain | Method    | URI               | Name          | Action                                        | Middleware   |
+--------+-----------+-------------------+---------------+-----------------------------------------------+--------------+
|        | GET|HEAD  | /                 |               | App\Http\Controllers\PagesController@index    | web          |
|        | GET|HEAD  | about             |               | App\Http\Controllers\PagesController@about    | web          |
|        | GET|HEAD  | api/user          |               | Closure                                       | api,auth:api |
|        | GET|HEAD  | posts             | posts.index   | App\Http\Controllers\PostsController@index    | web          |
|        | POST      | posts             | posts.store   | App\Http\Controllers\PostsController@store    | web          |
|        | GET|HEAD  | posts/create      | posts.create  | App\Http\Controllers\PostsController@create   | web          |
|        | GET|HEAD  | posts/{post}      | posts.show    | App\Http\Controllers\PostsController@show     | web          |
|        | PUT|PATCH | posts/{post}      | posts.update  | App\Http\Controllers\PostsController@update   | web          |
|        | DELETE    | posts/{post}      | posts.destroy | App\Http\Controllers\PostsController@destroy  | web          |
|        | GET|HEAD  | posts/{post}/edit | posts.edit    | App\Http\Controllers\PostsController@edit     | web          |
|        | GET|HEAD  | services          |               | App\Http\Controllers\PagesController@services | web          |
+--------+-----------+-------------------+---------------+-----------------------------------------------+--------------+
```

<div id="eloquent"></div>

## Получение данных с использованием ORM Eloquent

В моделе нам не прийдётся писать слишком много кода, потому что в нашем распоряжении имеется множество методов, которые нам в этом помогут. В начале нам нужно создать несколько переменных - определим название таблицы в БД, потому что в нашем случае модель названа в единственном числе, а таблица в множественном.

*app/Post.php*

```
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Table Name
    protected $table = 'posts';
    // Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timestamps = true;
}
```

Если мы сейчас обратимся к `http://laracms.loc/posts` то мы увидим пустую страницу, потому что мы обращаемся к методу `index()` в котором пока что нет никакого кода.

Давайте загрузим вид:

*app/Http/Controllers/PostsController.php*

```
public function index()
{
    return view('posts.index');
}
```

И создадим вид:

*resources/views/posts/index.blade.php*

```
@extends('layouts.app')

@section('content')
<h1>Posts</h1>
@endsection
```

Теперь нам нужно получить данные из модели:

* Добавляем пространство имён `App\Post`
* Обращаемся к модели и получаем все данные из таблицы с помощью метода `all()`

*app/Http/Controllers/PostsController.php*

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
    public function index()
    {
        return Post::all();
        return view('posts.index');
    }
    //..
}
```

Нам вернулись абсолютно все записи из БД.

Передадим данные в вид:

*app/Http/Controllers/PostsController.php*

```
public function index()
{
    $posts = Post::all();
    return view('posts.index')->with('posts', $posts);
}
```

Теперь осталось вывести данные виде:

*resources/views/posts/index.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h3><a href="posts/{{$post->id}}">{{$post->title}}</a></h3>
                    <small>Written on {{$post->created_at}}</small>
                </div>
            </div>
        @endforeach
    @else
        <p>Posts not found</p>
    @endif
@endsection
```

Теперь необходимые вывести конкретный материал, поэтому нам нужно поработать с методом `show()`:

*app/Http/Controllers/PostsController.php*

```
public function show($id)
{
    $post = Post::find($id);
    return view('posts.show')->with('post', $post);
}
```

Код логичный, а используемые методы практически не нуждаются в объяснении.

Теперь нужно создать вид:

*resources/views/posts/show.blade.php*

```
@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-outline-secondary"><i class="fa fa-backward"></i> Go Back</a>
    <div class="card mt-3">
        <div class="card-body">
            <h1>{{$post->title}}</h1>
            <p><small>{{$post->created_at}}</small></p>
            <div>{{$post->body}}</div>
        </div>
    </div>
@endsection
```

Здесь мы использовали иконки Font Awesome и как их подключить, вы узнаете чуть ниже.

Есть несколько методов, которые вам могут пригодится при работе с БД (в комментариях). Не забудьте добавить пространство имён для класса `DB` перед его использованием:

*app/Http/Controllers/PostsController.php*

```
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Post;

class PostsController extends Controller
{
    public function index()
    {
        // $posts = Post::all();
        // $posts = Post::orderBy('created_at', 'desc')->get();
        // $posts = DB::select('SELECT * FROM posts');
        // $posts = DB::table('posts')->simplePaginate(1);
        // $posts = Post::orderBy('created_at', 'desc')->take(1)->get();
        // return Post::where('id', '1')->get();
        $posts = Post::orderBy('created_at', 'desc')->paginate(1);
        return view('posts.index')->with('posts', $posts);
    }
}
```

Для вывода навигационных ссылок постраничной навигации, нужно добавить в вид вызов метода `links()`.

*resources/views/posts/index.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    <a href="/posts/create" class="btn btn-success mb-3"><i class="fa fa-pencil"></i> New Post</a>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h3><a href="posts/{{$post->id}}">{{$post->title}}</a></h3>
                    <small>Written on {{$post->created_at}}</small>
                </div>
            </div>
        @endforeach
        {{ $posts->links() }}
    @else
        <p>Posts not found</p>
    @endif
@endsection
```

<div id="fa"></div>

## Установка Font Awesome

Ставим через `npm`:

```
npm i font-awesome --save
```

Поключаем:

*resources/sass/app.scss*

```
@import '~font-awesome/scss/font-awesome';
```

Запускаем билдер:

```
npm run dev
```

<div id="form"></div>

## Формы и сохранение данных

За вывод формы у нас будет отвечать метод `create()`, давайте пока что просто выдем вид:

*app/Http/Controllers/PostsController.php*

```
public function create()
{
    return view('posts.create');
}
```

И создадим вид, чтобы проверить что он у нас выводится:

*resources/views/posts/create.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
@endsection
```

Создадим форму:

*resources/views/posts/create.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form action="{{route('posts.store')}}" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title">
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" id="body" class="form-control" placeholder="Body" cols="30" rows="10"></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
        {{csrf_field()}}
        <a href="/posts" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
    </form>
@endsection
```

`{{route('posts.store')}}` - это псевдоним марштура, а `{{csrf_field()}}` - это функция, которая будет создавать специальный token, который позволит защитить нашу форму от межсайтового скриптинга.

После отправки формы данные отправляются на обработку в метод `store()`, поэтому мы можем валидировать данные и если данные прошли валидации, тогда мы вернём результат запроса:

*app/Http/Controllers/PostsController.php*

```
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required',
        'body' => 'required'
    ]);

    dump($request->all());
}
```

Теперь нам нужно выводить сообщения об ошибках валидации для этого нам нужно создать файл, в котором мы будем перебирать и выводить в цикле ошибки валидации, а также будем заносить в сессию сообщения об успешном выполнении запроса или ошибке выполнения запроса:

*resources/views/include/messages.blade.php*

```
@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            {{$error}}
        </div>
    @endforeach
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{session('error')}}
    </div>
@endif
```

Теперь осталось подключить этот файл в нашем шаблоне и протестировать вывод ошибок валидации:

*resources/views/layouts/app.blade.php*

```
<div class="container">
    @include('include.messages')
    @yield('content')
</div>
```

Cохраним данные в БД и выведем сообщение что всё в порядке:

*app/Http/Controllers/PostsController.php*

```
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required',
        'body' => 'required'
    ]);

    // dump($request->all());

    $post = new Post;
    $post->title = $request->input('title');
    $post->body = $request->input('body');
    $post->save();

    return redirect('/posts')->with('success', 'Post Created');
}
```

<div id="edit"></div>

## Редактирование данных

Добавим ссылку для редактирования:

*resources/views/posts/show.blade.php*

```
@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-outline-secondary"><i class="fa fa-backward"></i> Go Back</a>
    <a href="/posts/{{$post->id}}/edit" class="btn btn-secondary ml-2"><i class="fa fa-pencil"></i> Edit Post</a>
    <div class="card mt-3">
        <div class="card-body">
            <h1>{{$post->title}}</h1>
            <p><small>{{$post->created_at}}</small></p>
            <div>{{$post->body}}</div>
        </div>
    </div>
@endsection
```

Отредактируем метод `edit()` который будет похож по содержимому с методом `show()`:

*app/Http/Controllers/PostsController.php*

```
public function edit($id)
{
    $post = Post::find($id);
    // dd($post);
    return view('posts.edit')->with('post', $post);
}
```

Теперь создаём новый файл вида, который мы скопируем с файла *resources/views/posts/create.blade.php* и немного подредактируем:

*resources/views/posts/edit.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form action="{{route('posts.update', $post->id)}}" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $post->title }}">
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" id="body" class="form-control" placeholder="Body" cols="30" rows="10">{{ $post->body }}</textarea>
        </div>
        <input type="hidden" name="_method" value="PUT">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
        {{csrf_field()}}
        <a href="/posts" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
    </form>
@endsection
```

В метод `route()` мы передаём ID поста. Так как в Laravel в роуте `posts.update` используется метод `PUT|PATCH`, а для формы у нас доступны только `GET` и `POST` нам нужно добавить скрытое поле.

Теперь осталось описать метод `update()`, который будет сохранять данные в БД - он очень похожий с методом `store()`:

*app/Http/Controllers/PostsController.php*

```
public function update(Request $request, $id)
{
    $this->validate($request, [
        'title' => 'required',
        'body' => 'required'
    ]);

    $post = Post::find($id);
    $post->title = $request->input('title');
    $post->body = $request->input('body');
    $post->save();

    return redirect('/posts')->with('success', 'Post Updated');
}
```

<div id="del"></div>

## Удаление данных

Для удаления нам нужно добавить новую форму на страницу с отображением отдельной статьи:

*resources/views/posts/show.blade.php*

```
@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-outline-secondary"><i class="fa fa-backward"></i> Go Back</a>
    <div class="card mt-3">
        <div class="card-body">
            <h1>{{$post->title}}</h1>
            <p><small>{{$post->created_at}}</small></p>
            <div>{{$post->body}}</div>
        </div>
    </div>
    <a href="/posts/{{$post->id}}/edit" class="btn btn-secondary mt-3"><i class="fa fa-pencil"></i> Edit Post</a>
    <form action="{{route('posts.destroy', $post->id)}}" method="POST" class="pull-right">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-danger mt-3"><i class="fa fa-remove"></i> Delete Post</button>
        {{csrf_field()}}
    </form>
@endsection
```

Теперь опишем что должно происходить в методе `destroy()`:

*app/Http/Controllers/PostsController.php*

```
public function destroy($id)
{
    $post = Post::find($id);
    $post->delete();
    return redirect('/posts')->with('success', 'Post Removed');
}
```

<div id="auth"></div>

## Авторизация пользователей

Ранее мы запускали миграции и в том числе ту, которая создала для нас таблицу `users`, которая вполне подходит нам для работы.

Чтобы сгенерировать все остальные файлы для авторизации нам нужно запустить ещё одну команду, при этом лучше изменить название файла *resources/views/layouts/app.blade.php* добавив нижнее подчёркивание в начале иначе этот файл может быть перезаписан в процессе выполнения данной команды:

```
php artisan make:auth
```

Теперь нужно взять данные из нового файла вида и сопоставить с тем что у нас было до этого:

*resources/views/layouts/app.blade.php*

```
<!DOCTYPE html>
<html lang="lang="{{ str_replace('_', '-', app()->getLocale()) }}"">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel APP') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@include('include.navbar')

<div class="container">
@include('include.messages')
@yield('content')
</div>

<script src="{{asset('js/app.js')}}"></script>
</body>
</html>
```

А наше верхнее меню приобретёт такой вид:

*resources/views/include/navbar.blade.php*

```
<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active"><a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="/posts">Blog</a></li>
            </ul>
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a href="/dashboard" class="dropdown-item">Dashboard</a>
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
```

Теперь нам нужно нужно зарегистировать нового пользователя, который затем автоматически залогинится в системе.

После авторизации у нас перекидывает на страницу `home` что может не совсем логично, поэтому мы поменяем все названия контроллеров, редиректы и названия видов, чтобы у нас вместо `home` стало `dashboard`.

* *app/Http/Controllers/HomeController.php* и *resources/views/home.blade.php* переименовать на *app/Http/Controllers/DashboardController.php* и *resources/views/dashboard.blade.php* соответвенно и в этих файлах изменить все вхождения `home` на `dashboard`, а также кроме этого ещё в файлах:

* *app/Http/Controllers/Auth/LoginController.php*
* *app/Http/Controllers/Auth/RegisterController.php*
* *app/Http/Controllers/Auth/ResetPasswordController.php*
* *routes/web.php*

Теперь поправим вид панели управления чтобы в верхней части у нас была ссылка на добавления новой заметки в блоге:

*resources/views/dashboard.blade.php*

```
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="/posts/create" class="btn btn-success mb-3"><i class="fa fa-pencil"></i> New Post</a>
                    <h3>You Blog Posts</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

Теперь нам нужно создать миграцию, чтобы связать необходимый пост с определённым автором:

```
php artisan make:migration add_user_id_to_posts
```

Нужно поправить данный файл, чтобы он выполнял какое-то полезное действие:

*database/migrations/2017_12_03_175125_add_user_id_to_posts.php*

```
class AddUserIdToPosts extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('user_id');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
```

Запустим миграцию:

```
php artisan migrate
```

После запуска миграции мы увидим как у нас к нашей таблице `posts` добавилось новое поле `user_id`. Пока что добавим `user_id` вручную указав `1` в phpMyAdmin. Затем нужно поправить метод `store()` чтобы ID пользователя записывалось автоматически.

*app/Http/Controllers/PostsController.php*

```
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required',
        'body' => 'required'
    ]);

    // dump($request->all());
    $post = new Post;
    $post->title = $request->input('title');
    $post->body = $request->input('body');
    $post->user_id = auth()->user()->id;
    $post->save();

    // Flash message
    return redirect('/posts')->with('success', 'Post Created');
}
```

Теперь если мы попробуем добавить новую статью, то мы увидим что ID пользователя добавляется автоматически.

<div id="rel"></div>

## Связи моделей

Чтобы создать взаимосвязь очень просто - нужно создать функцию в модели `Post`, которая определяет что посты должны пренадлежать определённому пользователю:

*app/Post.php*

```
class Post extends Model
{
    //..
    public function user(){
        return $this->belongsTo('App\User');
    }
}
```

Кроме этого нам нужно создать похожую функцию в модели `User`, которая определяет что у одного пользователя может быть несколько постов.

Eloquent определяет внешний ключ отношения по имени модели. В данном случае предполагается, что это `user_id`. Если вы хотите перекрыть стандартное имя, передайте второй параметр методу `hasMany()`. Но в нашем случае всё стандартно, то есть в таблице `posts` уже созданно поле `user_id` по которому и создаваться JOIN:

*app/User.php*

```
class User extends Authenticatable
{
    //..
    public function posts(){
        return $this->hasMany('App\Post');
    }
}
```

Теперь в контрольной панели управления мы можем выводить только те посты, которые принадлежать конкретному пользователю:

*app/Http/Controllers/DashboardController.php*

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class DashboardController extends Controller
{
    //..
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        return view('dashboard')->with('posts', $user->posts);
    }
}
```

Обратите внимание что мы обращаемся не к методу объекта, а к его свойству, хотя на самом деле в модели мы определяли функцию.

Поправим вид контрольной панели и выведем список постов в виде таблицы:

*resources/views/dashboard.blade.php*

```
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p><a href="/posts/create" class="btn btn-primary">Create Post</a></p>
                    <h3>You Blog Posts</h3>
                    @if(count($posts) > 0)
                        <table class="table table-striped">
                            <tr>
                                <th width="80%">Title</th>
                                <th width="10%"></th>
                                <th width="10%"></th>
                            </tr>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{ $post->title }}</td>
                                    <td><a href="/posts/{{ $post->id }}/edit" class="btn btn-outline-secondary">Edit</a></td>
                                    <td>
                                        <form action="{{route('posts.destroy', $post->id)}}" method="POST" class="pull-right">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="submit" value="Delete" class="btn btn-danger">
                                            {{csrf_field()}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p class="alert alert-info">You have no posts</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

Если список постов у нас выводится без ошибок, то теперь мы можем зарегистрировать нового пользователя и попробовать от его имени создать несколько постов, чтобы проверить, что у нас в панели управления будут выводится только те посты, которые пренадлежат конкретному пользователю.

Теперь мы можем вывести имя пользователя, который опубликовал статью:

*resources/views/posts/index.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Posts</h1>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="well">
                <h3><a href="posts/{{ $post->id}}">{{ $post->title }}</a></h3>
                <small>Written on {{ $post->created_at }} by {{ $post->user->name }}</small>
            </div>
        @endforeach
        {{ $posts->links() }}
    @else
        <p>Posts not found</p>
    @endif
@endsection
```

<div id="access"></div>

## Контроль доступа

Сейчас даже не зарегистрированный пользователь может отредактировать или удалить статью. Чтобы защитить контент можно использовать уже готовое решение, которое примеряется в контрольной панели - имеется в виду метод `middleware()` в конструкторе класса, если мы его применим в классе `PostsController`:

*app/Http/Controllers/PostsController.php*

```
class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    //..
```

То мы закроем доступ до всему контенту в разделе постов, нам же нужно ограничить редактирование ресурсов. Чтобы решить нашу проблему, нужно создать исключение, то есть перечислить те методы контроллера, которые мы хотим разблокировать для всех посетитей сайта.

*app/Http/Controllers/PostsController.php*

```
class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    //..
```

Доступ мы отключили, но кнопки редактировать и удалить остались, хоть они и не имеют смысла для не авторизованных пользователей, для этого есть простая конструкция:

*resources/views/posts/show.blade.php*

```
//..
@if(!Auth::guest())
    <a href="/posts/{{$post->id}}/edit" class="btn btn-secondary mt-3"><i class="fa fa-pencil"></i> Edit Post</a>
    <form action="{{route('posts.destroy', $post->id)}}" method="POST" class="pull-right">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-danger mt-3"><i class="fa fa-remove"></i> Delete Post</button>
        {{csrf_field()}}
    </form>
@endif
//..
```

Теперь кнопки исчезли, но при этом авторизированный пользователь сейчас может отредактировать абсолютно все статьи, а нам нужно чтобы он мог редактировать только свои, поэтому мы можем сделать такую проверку:

*resources/views/posts/show.blade.php*

```
@if(!Auth::guest())
    @if(Auth::user()->id == $post->user_id)
        <a href="/posts/{{$post->id}}/edit" class="btn btn-secondary mt-3"><i class="fa fa-pencil"></i> Edit Post</a>
        <form action="{{route('posts.destroy', $post->id)}}" method="POST" class="pull-right">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-danger mt-3"><i class="fa fa-remove"></i> Delete Post</button>
            {{csrf_field()}}
        </form>
    @endif
@endif
```

При этом всё ещё остаётся возможность изменять чужие посты напрямую через адресную строку браузера и чтобы поправить и это нам нужно подкорректировать метод `edit()` контроллера `PostsController`:

*app/Http/Controllers/PostsController.php*

```
public function edit($id)
{
    $post = Post::find($id);

    if(auth()->user()->id !== $post->user_id){
        return redirect('/posts')->with('error', 'Unauthorized Page');
    }

    return view('posts.edit')->with('post', $post);
}
```

То же самое нужно сделать внутри функции `delete()`:

*app/Http/Controllers/PostsController.php*

```
public function destroy($id)
{
    $post = Post::find($id);

    if(auth()->user()->id !== $post->user_id){
        return redirect('/posts')->with('error', 'Unauthorized Page');
    }

    $post->delete();
    return redirect('/posts')->with('success', 'Post Removed');
}
```

<div id="files"></div>

## Загрузка файлов

Добавим поле в форму для загрузки файла:

*resources/views/posts/create.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form action="{{route('posts.store')}}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title">
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" id="body" class="form-control" placeholder="Body" cols="30" rows="10"></textarea>
        </div>
        <div class="form-group">
            <input type="file" name="image" id="image">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
        {{csrf_field()}}
        <a href="/posts" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
    </form>
@endsection
```

Теперь нужно добавить поле в нашей таблице в БД, для этого нам нужно создать ещё одну миграцию:

```bash
php artisan make:migration add_image_to_posts
```

Теперь поправить файл миграций:

*database/migrations/2017_12_05_125805_add_image_to_posts.php*

```
class AddImageToPosts extends Migration
{

    public function up()
    {
        Schema::table('posts', function($table){
            $table->string('image');
        });
    }

    public function down()
    {
        Schema::table('posts', function($table){
            $table->dropColumn('image');
        });
    }

}
```

Запускаем миграцию:

```bash
php artisan migrate
```

Поправим метод `store()`, внесем ещё одно правило валидации - это поле должно быть изображением, необъязательным и максимальный размер `1999` (по умолчанию для Apache ограничение 2Мб), а сразу за правилами валидации напишем функцию обработчик:

*app/Http/Controllers/PostsController.php*

```
public function store(Request $request)
{
    $this->validate($request, [
        'title' => 'required',
        'body' => 'required',
        'image' => 'image|nullable|max:1999'
    ]);

    if($request->hasFile('image')){
        // Get file name with extension
        $filenameWithExt = $request->file('image')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('image')->getClientOriginalExtension();
        // Filename to store
        $filenameToStore = $filename . '_' . time() . '.' . $extension;
        // Upload the image
        $path = $request->file('image')->storeAs('public/images', $filenameToStore);
    }

    // dump($request->all());
    $post = new Post;
    $post->title = $request->input('title');
    $post->body = $request->input('body');
    $post->user_id = auth()->user()->id;
    $post->image = $filenameToStore;
    $post->save();

    // Flash message
    return redirect('/posts')->with('success', 'Post Created');
}
```

Путь по которому будут сохранятся все изображения будет *storage/app/public/*, но данная папка не доступна изне, поэтому нам нужно создать симлинк - для этого воспользуемся командой:

```bash
php artisan storage:link
```

Если мы теперь посмотрим в папку *public/* то мы увидим симлинк на папку *storage/*.

Теперь осталось добавить новый пост и проверить загружается ли у нас изображение в нужную папку и сохраняется ли название в БД.

Поправим виды:

*resources/views/posts/index.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1 class="pull-left">Posts</h1>
    <a href="/posts/create" class="btn btn-success mb-4 pull-right"><i class="fa fa-pencil"></i> New Post</a>
    <div class="clearfix"></div>
    @if(count($posts) > 0)
        @foreach($posts as $post)
            @if($post->image)
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="/storage/images/{{ $post->image }}" alt="{{ $post->title }}" class="img-thumbnail img-fluid">
                        </div>
                        <div class="col-md-8">
                            <h3><a href="posts/{{ $post->id}}">{{ $post->title }}</a></h3>
                            <small>Written on {{ $post->created_at }} by {{ $post->user->name }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="card mb-3">
                    <div class="card-body">
                        <h3><a href="posts/{{$post->id}}">{{$post->title}}</a></h3>
                        <small>Written on {{ $post->created_at }} by {{ $post->user->name }}</small>
                    </div>
                </div>
            @endif
        @endforeach
        {{ $posts->links() }}
    @else
        <p>Posts not found</p>
    @endif
@endsection
```

*resources/views/posts/show.blade.php*

```
@extends('layouts.app')

@section('content')
    <a href="/posts" class="btn btn-outline-secondary"><i class="fa fa-backward"></i> Go Back</a>
    <div class="card mt-3">
        <div class="card-body">
            <h1>{{$post->title}}</h1>
            <p><small>{{$post->created_at}}</small></p>
            @if($post->image)
            <img src="/storage/images/{{ $post->image }}" alt="{{ $post->title }}" class="img-thumbnail img-fluid">
            @endif
            <div class="mt-3">{{$post->body}}</div>
        </div>
    </div>
    //..
@endsection
```

Если всё гуд, теперь можно занятся редактированием постов, изменим вначале форму:

*resources/views/posts/edit.blade.php*

```
@extends('layouts.app')

@section('content')
    <h1>Create Post</h1>
    <form action="{{route('posts.update', $post->id)}}" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $post->title }}">
        </div>
        <div class="form-group">
            <label for="body">Body</label>
            <textarea name="body" id="body" class="form-control" placeholder="Body" cols="30" rows="10">{{ $post->body }}</textarea>
        </div>
        <div class="form-group">
            <input type="file" name="image" id="image">
        </div>
        <input type="hidden" name="_method" value="PUT">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
        {{csrf_field()}}
        <a href="/posts" class="btn btn-danger"><i class="fa fa-remove"></i> Cancel</a>
    </form>
@endsection
```

Поправим метод `update()`:

*app/Http/Controllers/PostsController.php*

```
public function update(Request $request, $id)
{
    $this->validate($request, [
        'title' => 'required',
        'body' => 'required',
        'image' => 'image|nullable|max:1999'
    ]);

    if($request->hasFile('image')){
        // Get file name with extension
        $filenameWithExt = $request->file('image')->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $request->file('image')->getClientOriginalExtension();
        // Filename to store
        $filenameToStore = $filename . '_' . time() . '.' . $extension;
        // Upload the image
        $path = $request->file('image')->storeAs('public/images', $filenameToStore);
    }

    $post = Post::find($id);
    $post->title = $request->input('title');
    $post->body = $request->input('body');
    if($request->hasFile('image')){
        $post->image = $filenameToStore;
    }
    $post->save();

    return redirect('/posts')->with('success', 'Post Updated');
}
```

Теперь нужно позаботиться о том чтобы при удалении поста у нас удалялось также изображение, поэтому нужно поправить метод `destroy()`:

*app/Http/Controllers/PostsController.php*

```
public function destroy($id)
{
    $post = Post::find($id);

    if(auth()->user()->id !== $post->user_id){
        return redirect('/posts')->with('error', 'Unauthorized Page');
    }

    Storage::delete('public/images/'. $post->image);

    $post->delete();
    return redirect('/posts')->with('error', 'Post Removed');
}
```

В самом начале нужно добавить фасад `Storage`:

*app/Http/Controllers/PostsController.php*

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;
//..
```