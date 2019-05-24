# Blade шаблонизация и компиляция активов

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