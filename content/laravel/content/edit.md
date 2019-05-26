# Редактирование данных

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