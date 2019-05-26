# Отправка почты

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