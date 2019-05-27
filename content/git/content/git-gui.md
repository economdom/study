# Git GUI клиенты

Клиенты Git с графическим интерфейсом более наглядны и просты в использовании, но работать с ними рекомендуется после того как вы хорошо усвоите работу с Git из консоли.

Их множество, которые вы сможете посмотреть (здесь)[https://git-scm.com/download/gui/linux].

Я рекомендую бесплатное кроссплатформенное приложение SouceTree, которое отлично работает с Git и хостингами GitHub и BitBucket.

# SourceTree

Для примера разберём использование простого проекта.

* Создаём новый репозиторий на GitHub
* Копируем HTTP или SSH ссылку репозитория
* Открываем SourceTree и клонируем новый репозиторий, указав при этом путь к удалённому репозиторию, который мы только что скопировали, путь где этот проект будет хранится на жёстком диске и его название.
* Пока что у нас откроется пуское окошко, потому что ещё не было созданно новых файлов
* Создадим новый файл - простую HTML страничку

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
</head>
<body>
  <h1>Hello, world!</h1>
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident quo repudiandae error dicta maxime, perspiciatis, repellendus laborum, fugiat doloribus deserunt rem illum! Nostrum quos sit reiciendis unde qui a accusantium.</p>
</body>
</html>
```

* Переходим снова в SourceTree и видим что у нас появился новый файл
* Устанавливаем галочку напротив HTML файла, что позволит добавить данный файл для отслеживания в локальный Git репозиторий
* Нажимаем на ветку **master** и видим что у нас появился первый коммит в списке коммитов
* Кликаем по кнопке <kbd>Push</kbd> в верхней панели, выбираем ветку `master` для отправки изменений на GitHub. Если SSH ключей вы не создавали, то вам ещё предложат ввести логин и пароль к вашей учётке на GitHub для того чтобы вы могли отправить изменения.
* Добавить ещё несколько строк в HTML файл

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <h1>Some Main Title</h1>
  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident quo repudiandae error dicta maxime, perspiciatis, repellendus laborum, fugiat doloribus deserunt rem illum! Nostrum quos sit reiciendis unde qui a accusantium.</p>
  <ul>
    <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi quidem ipsum atque numquam, sint odio nesciunt laborum repellat tenetur commodi beatae a, officia praesentium eius fuga illum totam veniam velit.</li>
    <li>Recusandae, nam ipsum dolores ut nesciunt sequi iste vitae iusto, unde placeat veniam quis earum atque omnis optio praesentium! Officia ipsum, neque ut eius necessitatibus cumque dolore maxime dicta velit!</li>
    <li>Assumenda totam eius optio, id minima labore natus ex ratione, maxime iusto fuga omnis dolorum libero tempore, fugiat eveniet delectus! Eveniet iste fuga sed odio. Fuga eaque natus aperiam, facilis.</li>
  </ul>
</body>
</html>
```

* Переходим снова в SourceTree и смотрим что у нас происходит автоматическая слежка что были сделанны изменения в разделе **File Status**
* Закомитим и пушим эти изменения
* Проверяем видны ли изменения на удалённом репозитории GitHub
* Добавляем новую ветку кликнув по кнопку <kbd>Branch</kbd> в верхней панели, указав имя, например `new_features`
* Снова изменяем файл и делаем коммит и пушим на сервер
* Проверяем на удалённом репозитории что теперь у нас присутствуют обе ветки и `master` и `new_features`
* Для переключения ветов в SourceTree нужно дважды кликнуть по ней и факт переключения можно наблюдать по установке галочки и выделении надписи ветки жирным
* Снова вносим изменения, коммитим и пушим

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
  <div class="col-md-12">
    <h1>Some Main Title</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Provident quo repudiandae error dicta maxime, perspiciatis, repellendus laborum, fugiat doloribus deserunt rem illum! Nostrum quos sit reiciendis unde qui a accusantium.</p>
    <ul>
      <li>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi quidem ipsum atque numquam, sint odio nesciunt laborum repellat tenetur commodi beatae a, officia praesentium eius fuga illum totam veniam velit.</li>
      <li>Recusandae, nam ipsum dolores ut nesciunt sequi iste vitae iusto, unde placeat veniam quis earum atque omnis optio praesentium! Officia ipsum, neque ut eius necessitatibus cumque dolore maxime dicta velit!</li>
      <li>Assumenda totam eius optio, id minima labore natus ex ratione, maxime iusto fuga omnis dolorum libero tempore, fugiat eveniet delectus! Eveniet iste fuga sed odio. Fuga eaque natus aperiam, facilis.</li>
    </ul>
    <p>Another changes into old file</p>
  </div>
</div>
</body>
</html>
```

* Проделав несколько изменений в ветке `master` мы можем принять решение об объединении веток и для того чтобы смержить ветки нам нужно изначально находится в той ветке, в которую мы хотим забрать изменения, затем нужно нажать кнопку <kbd>Merge</kbd> и выбрать в списке коммитов ту ветку из которой нам нужно изменения.
* Во время мержа могут встречатся конфликты, при этом SourceTree пометит эти конфлитные зависимости
* Можем вручную в текстовом редакторе внести правки чтобы в файле не осталось конфликтов. После внесения ручных правок можно выбрать необходимый файл в списке изменнённых в SourceTree и в диалоговом меню выбрать *Resolve Conflicts / Mark Resolved*
* Далее следует сохранить, закомитить и запушить изменения и проверить на удалённом сервере