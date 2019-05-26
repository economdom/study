# Авторизация пользователей

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

Нужно переименовать файлы *app/Http/Controllers/HomeController.php* и *resources/views/home.blade.php* на *app/Http/Controllers/DashboardController.php* и *resources/views/dashboard.blade.php* соответвенно.

Далее в следующих файлах изменить все вхождения `home` на `dashboard`:

* *app/Http/Controllers/DashboardController.php*
* *resources/views/dashboard.blade.php*
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