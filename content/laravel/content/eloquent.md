# Получение данных с использованием ORM Eloquent

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