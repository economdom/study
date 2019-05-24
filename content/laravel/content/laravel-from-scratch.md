
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

<div id="email"></div>

## Отправка почты

Создаём метод контроллера `PagesController`, который будет загружать вид с формой:

*app/Http/Controllers/PagesController.php*

```
public function contact(){
    return view('pages.contact');
}
```

Теперь создадим вид с формой:

*resources/views/pages/contact.blade.php*

```
@extends('layouts.app')

@section('content')
<h1>About</h1>
<div class="row mb-3">
<div class="col-md-6">
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quaerat, quidem, culpa. Esse soluta corrupti eaque ea voluptas id dolores eos ex dignissimos debitis voluptatem beatae iste, nesciunt iure dolore perspiciatis illo modi! Adipisci dicta, accusamus, nihil culpa impedit nam delectus! Quis repellendus aperiam eaque harum officiis eius esse, eligendi nostrum.</p>
</div>
<div class="col-md-6">
    <div class="well">
        <form action="{{ route('contact.email') }}" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="You name">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="You name">
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" id="message" cols="30" rows="10" class="form-control" placeholder="Enter your message"></textarea>
            </div>
            <input type="submit" value="Submit" class="btn btn-primary">
            {{csrf_field()}}
        </form>
    </div>
</div>
</div>
@endsection
```

Создадим роуты и добавляем соответствующую ссылку в меню:

*routes/web.php*

```
Route::get('/contact', 'PagesController@contact');
Route::post('/contact', 'PagesController@email')->name('contact.email');
```

Создадим обработчик формы, который будет пока что валидировать данные и распечатывать массив отправленных данных, если форма проходит валидацию:

*app/Http/Controllers/PagesController.php*

```
public function email(Request $request){
    $this->validate($request, [
        'name' => 'required|max:255',
        'email' => 'required|email',
        'message' => 'required'
    ]);
    $data = $request->all();
    return dd($data);
}
```

Параметры настройки почты содержатся в файле *config/mail.php*, в котором мы фактически определяем параметры конфигурации библиотеки Swift Mailer:

*.env*

```
MAIL_DRIVER=mail
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_AMIN=admin@laravel.loc
```

Мы же для простоты, отправим письмо с помощью стандартной функции `mail()`.

*app/Http/Controllers/PagesController.php*

```
public function email(Request $request){
    $this->validate($request, [
        'name' => 'required|max:255',
        'email' => 'required|email',
        'message' => 'required'
    ]);

    // return dd($data);

    $message = '<p>From Name: ' . $request['name'] . '</p>';
    $message .= '<p>From Email: ' . $request['email'] . '</p>';
    $message .= '<p>Message: </p>' . $request['message'];

    mail('v.kamuz@gmail.com', 'Message from my site', $message);

    return redirect('/contact')->with('success', 'Your message has been sent!');
}
```