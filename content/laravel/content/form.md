# Формы и сохранение данных

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