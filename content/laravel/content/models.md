# Модели и миграции базы данных

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