# Контроль доступа

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