# Связи моделей

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