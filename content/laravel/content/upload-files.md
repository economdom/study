# Загрузка файлов

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

```
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

```
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

```
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